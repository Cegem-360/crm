<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\Currency;
use App\Enums\NavigationGroup;
use App\Models\TeamSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Override;
use UnitEnum;

/**
 * @property-read Schema $form
 */
final class ManageTeamSettings extends Page
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-team-settings';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Team Settings');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Team Settings');
    }

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Select::make('currency')
                        ->label(__('Currency'))
                        ->options(Currency::class)
                        ->default(Currency::HUF)
                        ->required()
                        ->helperText(__('The currency used for prices, quotes, invoices and orders.')),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('Save'))
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord();

        if (! $record) {
            $record = new TeamSetting;
            $record->team_id = Filament::getTenant()->getKey();
        }

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title(__('Settings saved'))
            ->send();
    }

    public function getRecord(): ?TeamSetting
    {
        $team = Filament::getTenant();

        if (! $team) {
            return null;
        }

        return TeamSetting::query()
            ->withoutGlobalScopes()
            ->where('team_id', $team->getKey())
            ->first();
    }
}
