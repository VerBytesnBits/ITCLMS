<?php

use App\Livewire\Roles\RoleIndex;
use App\Livewire\Rooms\RoomIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {


    // Allow only users with "manage.users" permission
    Route::get('users', UserIndex::class)
        ->middleware('can:manage.users')
        ->name('users.index');

    // Allow only users with "manage.roles" permission
    Route::get('roles', RoleIndex::class)
        ->middleware('can:manage.roles')
        ->name('roles.index');

    Route::get('rooms', RoomIndex::class)
        ->name('rooms.index');


    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
