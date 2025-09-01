<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
// use App\Livewire\Components\ComponentsIndex as components;
use App\Livewire\Peripherals\PeripheralIndex as peripherals;
use App\Livewire\ComponentsPart\index as components;


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


    // Route::get('components', components::class)->name('components');
    Route::get('components', components::class)->name('components');
    Route::get('peripherals', peripherals::class)->name('peripherals');



    // Route::get('/units/{unit}', SystemUnitIndex::class)->name('system-units.index');

    // Route::get('/system-units/{systemUnit}', SystemUnitShow::class)->name('system-units.show');



    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
