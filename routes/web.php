<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\OrganiserDashboard;
use App\Http\Controllers\AdminController;
use App\Livewire\RankingReportPage;
use App\Livewire\EventDashboardPage;
use App\Livewire\LeaderBoardPage;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\OrganiserReportController;
use App\Http\Controllers\RankingExportController;
use App\Http\Controllers\ParticipantExportController;
use App\Livewire\Admin\EventApproval;

require __DIR__.'/user.php';

Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

Route::get('/ads/{id}/click', [EventController::class, 'trackClick'])->name('ads.click');

Route::prefix('admin')->name('admin.')->group(function () {
    // Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/participants/{event}', [AdminController::class, 'participants'])->name('participants');
    // Route::get('/groups/{event}', [AdminController::class, 'groups'])->name('groups');
    Route::get('/participant/{peserta}', [AdminController::class, 'viewParticipant'])->name('participant.view');
    Route::get('/event-approval', EventApproval::class)->name('event-approval');
});

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

