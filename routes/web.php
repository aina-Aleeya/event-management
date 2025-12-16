<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\EventDetails;
use App\Livewire\EventPage;
use App\Livewire\PesertaForm;
use App\Http\Controllers\AdminController;
use App\Livewire\PaymentForm;
use App\Livewire\SenaraiPeserta;
use App\Livewire\RankingReportPage;
use App\Livewire\EventDashboardPage;
use App\Livewire\LeaderBoardPage;
use App\Livewire\HistoryPage;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RankingExportController;
use App\Http\Controllers\ParticipantExportController;
use App\Livewire\Admin\CreateEvent;
use App\Http\Controllers\ScoresheetController;

// Public Routes
Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

Route::get('/events', [EventPage::class, 'render'])->name('events.page');

Route::get('/daftar/{id}', PesertaForm::class)->name('peserta.form');

Route::get('/events/{id}', EventDetails::class)->name('event.details');

Route::get('/ads/{id}/click', [EventController::class, 'trackClick'])->name('ads.click');

// Admin Routes - Protected by auth and admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Event Management
    Route::get('/create-event', CreateEvent::class)->name('create-event');
    //Route::get('/events/{event}/edit', \App\Livewire\Admin\EditEvent::class)->name('event.edit');
    Route::get('/events/{event}/dashboard', EventDashboardPage::class)->name('event.dashboard');

    // Participant Management
    Route::get('/participants/{event}', [AdminController::class, 'participants'])->name('participants');
    Route::get('/participant/{peserta}', [AdminController::class, 'viewParticipant'])->name('participant.view');
    Route::get('/event/{event}/participants/export', [ParticipantExportController::class, 'export'])->name('event.participants.export');

    // Grouping System
    Route::get('/grouping', [AdminController::class, 'groupingIndex'])->name('grouping.index');
    Route::get('/events/{event}/groups', [AdminController::class, 'groups'])->name('groups');
    Route::post('/events/{event}/groups/store', [AdminController::class, 'storeGroup'])->name('group.store');
    Route::post('/events/{event}/groups/auto', [AdminController::class, 'autoGroup'])->name('group.auto');
    Route::post('/events/{event}/assign', [AdminController::class, 'assignToGroup'])->name('group.assign');
    Route::post('/groups/{event}/move', [AdminController::class, 'moveParticipant'])->name('group.move');
    Route::post('/groups/{event}/remove', [AdminController::class, 'removeParticipant'])->name('group.remove');
    Route::get('/events/{event}/grouping/{category}', [AdminController::class, 'groupingByCategory'])->name('grouping.category');

    // Reports & Rankings
    Route::get('/ranking-report/{event}', RankingReportPage::class)->name('ranking.report');
    Route::get('/event/{event}/leaderboard', LeaderboardPage::class)->name('event.leaderboard');
    Route::get('/event/{event}/ranking/export', [RankingExportController::class, 'export'])->name('event.ranking.export');

    // Scoresheet Export
    Route::get(
        '/scoresheet/export-group/{event}/{group}',
        [ScoresheetController::class, 'exportGroup']
    )
        ->name('scoresheet.export-group');

    Route::get(
        '/scoresheet/export-all-groups/{event}',
        [ScoresheetController::class, 'exportAllGroups']
    )
        ->name('scoresheet.export-all-groups');
});

// User Routes - Protected by auth
Route::middleware(['auth'])->group(function () {
    // User History
    Route::get('/history', HistoryPage::class)->name('history');
    Route::get('/history-participant/{eventId}', SenaraiPeserta::class)->name('history.participant');

    // Payment
    Route::get('/payment/{id}', PaymentForm::class)->name('payment.form');

    // Settings
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
