<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\SystemUnits\UnitIndex as Units;
use App\Livewire\Roles\RoleIndex as Roles;
use App\Livewire\Users\UserIndex as Users;
use App\Livewire\Rooms\RoomIndex as Rooms;
use App\Livewire\Processors\Create as ProcessorCreate;
use App\Events\TestPing;


Route::get('/test-broadcast', function () {
    broadcast(new TestPing());
    return 'Ping sent!';
});
Route::get('/test-unit', function () {
    $unit = \App\Models\SystemUnit::first();
    broadcast(new \App\Events\UnitCreated($unit));
    return 'UnitCreated fired!';
});


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {


    // Allow only users with "manage.users" permission
    Route::get('users', Users::class)
        ->middleware('can:manage.users')
        ->name('users');

    // Allow only users with "manage.roles" permission
    Route::get('roles',Roles::class)
        ->middleware('can:manage.roles')
        ->name('roles');

    Route::get('rooms', Rooms::class)
        ->middleware('can:view.laboratories')
        ->name('rooms');

    Route::get('units',Units::class)->name('units');
        // ->middleware('can:view.system-units')  
        

});

require __DIR__ . '/auth.php';
