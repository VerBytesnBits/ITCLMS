<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\SystemUnits\Index as SystemUnitIndex;
use App\Livewire\SystemUnits\Show as SystemUnitShow;
use App\Livewire\Processors\Create as ProcessorCreate;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {


    // Allow only users with "manage.users" permission
    Route::view('users', 'users')
        ->middleware('can:manage.users')
        ->name('users');

    // Allow only users with "manage.roles" permission
    Route::view('roles', 'roles')
        ->middleware('can:manage.roles')
        ->name('roles');

    Route::view('rooms', 'rooms')
        ->middleware('can:view.laboratories')
        ->name('rooms');

    Route::view('units', 'units')
        // ->middleware('can:view.system-units')  // adjust the permission name accordingly
        ->name('units');




    // Route::get('/units/{unit}', SystemUnitIndex::class)->name('system-units.index');

    // Route::get('/system-units/{systemUnit}', SystemUnitShow::class)->name('system-units.show');

    Route::get('/system-units/{unit}/processors/create', ProcessorCreate::class)
        ->name('processors.create');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
