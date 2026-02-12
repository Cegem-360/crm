<?php

declare(strict_types=1);

use App\Console\Commands\AssignDataToDefaultTeam;
use App\Http\Controllers\Api\V1\AuthController;
use App\Providers\Filament\AdminPanelServiceProvider;
use Illuminate\Database\Eloquent\Model;

arch()->preset()->php();
// arch()->preset()->strict();
arch()->preset()->laravel()
    ->ignoring(AuthController::class)
    ->ignoring(AdminPanelServiceProvider::class)
    ->ignoring(AssignDataToDefaultTeam::class);
arch()->preset()->security();
// Check that all models (except Concerns traits) extend Model
arch()
    ->expect('App\Models')
    ->classes()
    ->toExtend(Model::class)
    ->ignoring('App\Models\Scopes');
arch()->expect('App\Controllers\Controller')->toBeAbstract();
