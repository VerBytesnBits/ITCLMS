<?php

use App\Livewire\Issues\IssueTable;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactorAuthentication;
use App\Livewire\Auth\TwoFactorAuthentication as TwoFA;
use Illuminate\Support\Facades\Route;

use App\Livewire\Peripherals\PeripheralIndex as peripherals;
use App\Livewire\ComponentsPart\Index as Components;
use App\Livewire\SystemUnits\MaintenanceIndex as maintenance;
use App\Livewire\SystemUnits\UnitIndex as units;
use App\Livewire\Issues\ReportIssue;
use App\Livewire\ActivityLogViewer as activitylogs;

use App\Livewire\Reports\UnitReport;
use App\Livewire\Reports\Index as ReportsIndex;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified', '2fa'])->name('dashboard');

Route::get('auth/2fa', TwoFA::class)
    ->middleware(['auth', 'twofactor.verified'])
    ->name('auth.2fa');

Route::middleware(['auth', '2fa'])->group(function () {
    Route::view('users', 'users')->middleware('can:manage.users')->name('users');
    Route::view('roles', 'roles')->middleware('can:manage.roles')->name('roles');
    Route::view('rooms', 'rooms')->middleware('can:view.laboratories')->name('rooms');
    Route::get('units', units::class)->middleware('can:view.unit')->name('units');
    Route::get('maintenance', maintenance::class)->name('maintenance');
    Route::get('components', Components::class)->middleware('can:view.component')->name('components');
    Route::get('peripherals', peripherals::class)->middleware('can:view.peripheral')->name('peripherals');

    Route::get('activitylogs', activitylogs::class)->middleware('can:view.activitylogs')->name('activitylogs');

    Route::get('reports', ReportsIndex::class)->middleware('can:view.reports')->name('reports');

    Route::get('report-issue', IssueTable::class)->middleware('can:view.reports')->name('report-issue');


    Route::get('units/generate-report', UnitReport::class)->name('units.report');
    Route::get('peripherals/report-preview', \App\Livewire\Peripherals\PeripheralsReport::class)
        ->name('peripherals.report-preview');
    Route::get('components/report-preview', \App\Livewire\ComponentsPart\ComponentsPartsReport::class)
        ->name('components-part.components-parts-report');


    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/2fa', TwoFactorAuthentication::class)->name('settings.2fa');
});

require __DIR__ . '/auth.php';
