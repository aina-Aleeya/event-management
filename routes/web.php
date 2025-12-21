<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\OrganiserDashboard;
use App\Http\Controllers\AdminController;
use App\Livewire\RankingReportPage;
use App\Livewire\EventDashboardPage;
use App\Livewire\LeaderBoardPage;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RankingExportController;
use App\Http\Controllers\ParticipantExportController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\MarkahController;
use App\Livewire\Admin\CreateEvent;
use App\Http\Controllers\ScoresheetController;

<<<<<<<<< Temporary merge branch 1
// Public Routes
=========
require __DIR__.'/user.php';

>>>>>>>>> Temporary merge branch 2
Route::get('/', function () {
    return view('dashboard');
})->name('home');

// Score Submission Routes
Route::get('/markah/{token}', [App\Http\Controllers\MarkahController::class, 'form'])
    ->name('markah.form');

Route::post('/markah/{token}', [App\Http\Controllers\MarkahController::class, 'submit'])
    ->name('markah.submit');

Route::view('dashboard', 'dashboard')->name('dashboard');

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
    Route::get('/event/{event}/participants/pdf', [ParticipantExportController::class, 'exportParticipantsPdf'])->name('event.participants.pdf');

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

    //Ranking (baru)
    Route::get('/ranking/{event}', [RankingController::class, 'show'])
        ->name('ranking.show');

    // Export routes (baru)
    Route::get('/ranking/{event}/export-sheet', [App\Http\Controllers\RankingExportController::class, 'exportSheet'])
        ->name('ranking.export.sheet');
    
    Route::get('/ranking/{event}/export-pdf', [App\Http\Controllers\RankingExportController::class, 'exportPdf'])
        ->name('ranking.export.pdf');

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
=========
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::prefix('admin')->group(function() {
    Route::get('event/{event}/groups', [AdminController::class, 'groups'])->name('admin.groups');
    Route::post('event/{event}/groups', [AdminController::class, 'storeGroup'])->name('admin.group.store');
    Route::post('event/{event}/groups/assign', [AdminController::class, 'assignToGroup'])->name('admin.group.assign');
    Route::post('event/{event}/groups/auto', [AdminController::class, 'autoGroup'])->name('admin.group.auto');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('organiser')->name('organiser.')->group(function () {
        Route::get('/dashboard', [OrganiserController::class, 'dashboard'])->name('dashboard');
        Route::get('/check-event', OrganiserDashboard::class)->name('check-event');
        Route::get('/participants/{event}', [OrganiserController::class, 'participants'])->name('participants');
        Route::get('/groups/{event}', [OrganiserController::class, 'groups'])->name('groups');
        Route::get('/participant/{peserta}', [OrganiserController::class, 'viewParticipant'])->name('participant.view');
        Route::get('/events/{event}/report', [OrganiserReportController::class, 'generate'])->name('events.report');
        Route::get('/ranking-report/{event}', RankingReportPage::class)->name('ranking.report');
        Route::get('/events/{event}/dashboard', EventDashboardPage::class)->name('event.dashboard');
        Route::get('/event/{event}/leaderboard', LeaderboardPage::class)->name('event.leaderboard');
        Route::get('/event/{event}/ranking/export', [RankingExportController::class, 'export'])->name('event.ranking.export');
        Route::get('/event/{event}/participants/export', [ParticipantExportController::class, 'export'])->name('event.participants.export');

    });
});

Route::get('/events/{eventId}/edit', \App\Livewire\EditEvent::class)
    ->middleware('auth')
    ->name('event.edit');

// =========================
// GROUPING SYSTEM ROUTES
// =========================

Route::get('/admin/events/{event}/groups', [AdminController::class, 'groups'])
    ->name('admin.groups');

Route::post('/admin/events/{event}/groups/store', [AdminController::class, 'storeGroup'])
    ->name('admin.group.store');

Route::post('/admin/events/{event}/groups/auto', [AdminController::class, 'autoGroup'])
    ->name('admin.group.auto');

Route::post('/admin/events/{event}/assign', [AdminController::class, 'assignToGroup'])
    ->name('admin.group.assign');

Route::get('/admin/grouping', [AdminController::class, 'groupingIndex'])
    ->name('admin.grouping.index');

>>>>>>>>> Temporary merge branch 2
