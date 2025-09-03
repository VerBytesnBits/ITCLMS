<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
// use App\Livewire\Components\ComponentsIndex as components;
use App\Livewire\Peripherals\PeripheralIndex as peripherals;
use App\Livewire\ComponentsPart\index as components;
use App\Livewire\SystemUnits\MaintenanceIndex as maintenance;
use App\Livewire\QrManager;
use App\Livewire\ActivityLogViewer as activitylogs;
use App\Livewire\Tracking\Show;

use App\Livewire\Reports\Index as ReportsIndex;

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
    // Maintenance page
    Route::get('maintenance', maintenance::class)->name('maintenance');

    // Route::get('components', components::class)->name('components');
    Route::get('components', components::class)->name('components');
    Route::get('peripherals', peripherals::class)->name('peripherals');


    Route::get('/qr-manager', QrManager::class)->name('qr-manager');

    Route::get('/tracking/{type}/{serial}', Show::class)->name('tracking.show');

    Route::get('activitylogs', activitylogs::class)->name('activitylogs');

    Route::get('reports', ReportsIndex::class)->name('reports');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
