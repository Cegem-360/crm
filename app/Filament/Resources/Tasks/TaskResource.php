<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tasks;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Support;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
