# Product Webhook API Dokumentáció

## Webhook Események

A rendszer automatikusan POST kérést küld a beállított webhook URL-re, amikor egy termék módosul.

### Események típusai

| Event | Leírás |
|-------|--------|
| `product.created` | Új termék létrehozva |
| `product.updated` | Termék módosítva |
| `product.deleted` | Termék törölve (soft delete) |
| `product.restored` | Törölt termék visszaállítva |
| `product.force_deleted` | Termék véglegesen törölve |

---

## Webhook Payload Struktúra

```json
{
  "event": "product.created",
  "timestamp": "2026-01-14T11:00:00+00:00",
  "data": {
    "id": 1,
    "name": "Termék neve",
    "sku": "PRD-0001-ABC",
    "description": "Termék leírása vagy null",
    "category_id": 5,
    "unit_price": "15000.00",
    "tax_rate": "27.00",
    "is_active": true
  }
}
```

### Mezők leírása

| Mező | Típus | Leírás |
|------|-------|--------|
| `event` | string | Az esemény típusa (pl. `product.created`) |
| `timestamp` | string | ISO 8601 formátumú időbélyeg |
| `data.id` | integer | Termék egyedi azonosítója |
| `data.name` | string | Termék neve |
| `data.sku` | string | Cikkszám (egyedi) |
| `data.description` | string\|null | Termék leírása |
| `data.category_id` | integer\|null | Kategória azonosítója |
| `data.unit_price` | string | Egységár (decimal, 2 tizedesjegy) |
| `data.tax_rate` | string | ÁFA kulcs százalékban (pl. "27.00") |
| `data.is_active` | boolean | Aktív-e a termék |

---

## HTTP Headers

```http
POST /your-webhook-endpoint HTTP/1.1
Content-Type: application/json
X-Webhook-Event: product.created
X-User-Token: abc123def456...
X-Webhook-Signature: abc123...  (opcionális, ha van webhook_secret beállítva)
```

| Header | Leírás |
|--------|--------|
| `Content-Type` | Mindig `application/json` |
| `X-Webhook-Event` | Az esemény típusa |
| `X-User-Token` | **Felhasználó egyedi azonosító tokenje** - ezzel azonosítsd, melyik user triggereit kell futtatni |
| `X-Webhook-Signature` | HMAC-SHA256 aláírás (ha van secret) |

---

## Felhasználó Azonosítás (X-User-Token)

Minden webhook kérés tartalmazza az `X-User-Token` header-t, amely a felhasználó egyedi azonosítója.

**A workflow rendszerben:**
1. Tárold el minden user-hez a token-jét
2. Webhook fogadáskor olvasd ki az `X-User-Token` header-t
3. Csak azokat a triggereket/flow-kat futtasd, amelyek ehhez a userhez tartoznak

### Példa a token kezelésére:

```php
public function handle(Request $request)
{
    $userToken = $request->header('X-User-Token');

    if (empty($userToken)) {
        return response()->json(['error' => 'Missing user token'], 401);
    }

    // Keresd meg a user-t a token alapján
    $workflowUser = WorkflowUser::where('crm_token', $userToken)->first();

    if (!$workflowUser) {
        return response()->json(['error' => 'Invalid user token'], 401);
    }

    // Csak ennek a usernek a triggerjeit futtasd
    $triggers = Trigger::where('user_id', $workflowUser->id)
        ->where('event', $request->input('event'))
        ->get();

    foreach ($triggers as $trigger) {
        $trigger->execute($request->input('data'));
    }

    return response()->json(['status' => 'ok']);
}
```

---

## Aláírás Ellenőrzése (HMAC-SHA256)

Ha be van állítva `webhook_secret`, a rendszer HMAC-SHA256 aláírást küld.

### PHP példa az ellenőrzésre:

```php
<?php

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$secret = 'your-webhook-secret';

$expectedSignature = hash_hmac('sha256', $payload, $secret);

if (hash_equals($expectedSignature, $signature)) {
    // Aláírás érvényes
    $data = json_decode($payload, true);
    // Feldolgozás...
} else {
    // Érvénytelen aláírás
    http_response_code(401);
    exit('Invalid signature');
}
```

### Laravel példa:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.crm.webhook_secret');

        if ($secret) {
            $signature = $request->header('X-Webhook-Signature');
            $expectedSignature = hash_hmac('sha256', $request->getContent(), $secret);

            if (!hash_equals($expectedSignature, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $event = $request->input('event');
        $data = $request->input('data');

        match ($event) {
            'product.created' => $this->handleProductCreated($data),
            'product.updated' => $this->handleProductUpdated($data),
            'product.deleted' => $this->handleProductDeleted($data),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handleProductCreated(array $data): void
    {
        // Új termék létrehozása a helyi adatbázisban
        Product::updateOrCreate(
            ['external_id' => $data['id']],
            [
                'name' => $data['name'],
                'sku' => $data['sku'],
                'description' => $data['description'],
                'price' => $data['unit_price'],
                'tax_rate' => $data['tax_rate'],
                'is_active' => $data['is_active'],
            ]
        );
    }

    private function handleProductUpdated(array $data): void
    {
        Product::where('external_id', $data['id'])->update([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'description' => $data['description'],
            'price' => $data['unit_price'],
            'tax_rate' => $data['tax_rate'],
            'is_active' => $data['is_active'],
        ]);
    }

    private function handleProductDeleted(array $data): void
    {
        Product::where('external_id', $data['id'])->delete();
    }
}
```

### Route (Laravel):

```php
// routes/api.php
Route::post('/webhooks/{token}', [WebhookController::class, 'handle'])
    ->name('webhooks.handle');
```

---

## Válasz Elvárások

A webhook fogadó végpontnak a következőket kell visszaadnia:

| HTTP Status | Jelentés |
|-------------|----------|
| `200` vagy `2xx` | Sikeres feldolgozás |
| `4xx` | Kliens hiba (pl. érvénytelen aláírás) |
| `5xx` | Szerver hiba (újrapróbálkozás történik) |

**Fontos:** A rendszer 3x próbálkozik 30 másodperces késleltetéssel sikertelen kézbesítés esetén.

---

## Tesztelés

Webhook teszteléséhez használhatod:
- [webhook.site](https://webhook.site) - Ingyenes webhook tesztelő
- [requestbin.com](https://requestbin.com) - Request inspector
- `ngrok` - Lokális fejlesztéshez

---

## Példa cURL kérés (teszteléshez)

```bash
curl -X POST https://your-app.test/api/webhooks/your-token \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Event: product.created" \
  -H "X-Webhook-Signature: your-signature" \
  -d '{
    "event": "product.created",
    "timestamp": "2026-01-14T11:00:00+00:00",
    "data": {
      "id": 1,
      "name": "Test Product",
      "sku": "TEST-001",
      "description": null,
      "category_id": null,
      "unit_price": "1500.00",
      "tax_rate": "27.00",
      "is_active": true
    }
  }'
```
