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
use App\Livewire\Admin\CreateEvent;
use App\Livewire\Admin\ScoreForm;
use App\Http\Controllers\ScoresheetController;

// Public Routes
require __DIR__.'/user.php';

Route::get('/', function () {
    return view('dashboard');
})->name('home');

// Score Submission Routes
Route::get('/score/{token}', ScoreForm::class)->name('score.form');

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
    // Route::get('/events/{event}/grouping', [AdminController::class, 'eventGrouping'])->name('admin.event.grouping');

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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::get('/events/{eventId}/edit', \App\Livewire\EditEvent::class)
    ->middleware('auth')
    ->name('event.edit');

Route::get('/admin/events/{event}/grouping', 
    [AdminController::class, 'eventGrouping'])
    ->name('admin.event.grouping');

