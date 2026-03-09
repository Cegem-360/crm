<?php

declare(strict_types=1);

test('the language switch route works', function (): void {
    $response = $this->get('/language/hu');

    $response->assertRedirect();
    $response->assertCookie('locale', 'hu');
});
