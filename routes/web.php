<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
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
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\Admin\OrganiserController as AdminOrganiserController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventTeamController;

// Public Routes
require __DIR__.'/user.php';


Route::get('/', function () {
    // Redirect authenticated users to their respective dashboards
    if (Auth::check()) {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'organiser') {
            return redirect()->route('organiser.dashboard');
        }
    }
    
    return view('dashboard');
})->name('home');

// Score Submission Routes
Route::get('/markah/{token}', [App\Http\Controllers\MarkahController::class, 'form'])
    ->name('markah.form');

Route::post('/markah/{token}', [App\Http\Controllers\MarkahController::class, 'submit'])
    ->name('markah.submit');

Route::view('dashboard', 'dashboard')->name('dashboard');

Route::get('/ads/{id}/click', [EventController::class, 'trackClick'])->name('ads.click');

// ADMIN ONLY ROUTES (Super Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/organisers/{organiser}/dashboard', [AdminOrganiserController::class, 'viewDashboard'])
    ->name('organisers.dashboard');

    // Organiser Management (Admin only)
    Route::resource('organisers', AdminOrganiserController::class);
});

// ============================================================================
// SHARED ROUTES (Admin + Organiser)
// ============================================================================
Route::middleware(['auth', 'role:admin,organiser'])->group(function () {
    
    // Admin prefix routes (accessible by both admin and organiser)
    Route::prefix('admin')->name('admin.')->group(function () {

    // Organiser Management
    // Route::resource('organisers', AdminOrganiserController::class);

    // Event Management
    Route::get('/create-event', CreateEvent::class)->name('create-event');
    Route::get('/events/{event}/dashboard', EventDashboardPage::class)->name('event.dashboard');

    // Participant Management
    Route::get('/participants/{event}', [AdminController::class, 'participants'])->name('participants');
    Route::get('/participant/{peserta}', [AdminController::class, 'viewParticipant'])->name('participant.view');
    Route::get('/event/{event}/participants/export', [ParticipantExportController::class, 'export'])->name('event.participants.export');
    Route::get('/event/{event}/participants/pdf', [ParticipantExportController::class, 'exportParticipantsPdf'])->name('event.participants.pdf');

    // Grouping System
    Route::get('/grouping', [GroupController::class, 'groupingIndex'])->name('grouping.index');
    Route::get('/events/{event}/groups', [GroupController::class, 'groups'])->name('groups');
    Route::post('/events/{event}/groups/store', [GroupController::class, 'storeGroup'])->name('group.store');
    Route::post('/events/{event}/groups/auto', [GroupController::class, 'autoGroup'])->name('group.auto');
    Route::post('/events/{event}/assign', [GroupController::class, 'assignToGroup'])->name('group.assign');
    Route::post('/groups/{event}/move', [GroupController::class, 'moveParticipant'])->name('group.move');
    Route::post('/groups/{event}/remove', [GroupController::class, 'removeParticipant'])->name('group.remove');
    Route::get('/events/{event}/grouping/{category}', [GroupController::class, 'groupingByCategory'])->name('grouping.category');
    // Route::get('/events/{event}/grouping', [AdminController::class, 'eventGrouping'])->name('admin.event.grouping');

    // Reports & Rankings
    Route::get('/ranking-report/{event}', RankingReportPage::class)->name('ranking.report');
    Route::get('/event/{event}/leaderboard', LeaderboardPage::class)->name('event.leaderboard');
    Route::get('/event/{event}/ranking/export', [RankingExportController::class, 'export'])->name('event.ranking.export');

    //Ranking
    Route::get('/ranking/{event}', [RankingController::class, 'show'])
        ->name('ranking.show');

    // Export routes
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
    
    // Certificate Management Page
    Route::get('events/{event}/certificates', [CertificateController::class, 'managementPage'])
        ->name('certificate.manage');
    
    // Update Certificate Settings
    Route::put('events/{event}/certificate-settings', [CertificateController::class, 'updateSettings'])
        ->name('certificate.update-settings');
    
    // Download Single Certificate
    Route::get('certificate/export-single/{event}/{peserta}', [CertificateController::class, 'exportSingle'])
        ->name('certificate.single');
    
    // Download Group Certificates (ZIP)
    Route::get('certificate/export-group/{event}/{group}', [CertificateController::class, 'exportGroup'])
        ->name('certificate.group');
    
    // Download All Certificates (ZIP)
    Route::get('certificate/export-all/{event}', [CertificateController::class, 'exportAllParticipants'])
        ->name('certificate.all');
});

Route::get('/events/{eventId}/edit', \App\Livewire\EditEvent::class)
    ->middleware('auth')
    ->name('event.edit');

Route::get('/admin/events/{event}/grouping', 
    [GroupController::class, 'eventGrouping'])
    ->name('admin.event.grouping');
});


// Organiser Routes (Admin + Organiser)
Route::middleware(['auth', 'role:admin,organiser'])->prefix('organiser')->name('organiser.')->group(function () {
    Route::get('/dashboard', [OrganiserController::class, 'dashboard'])->name('dashboard');

    // Team Management Routes
    Route::prefix('events/{event}/team')->name('events.team.')->group(function () {
        Route::get('/', [EventTeamController::class, 'index'])->name('index');
        Route::get('/create', [EventTeamController::class, 'create'])->name('create');
        Route::post('/', [EventTeamController::class, 'store'])->name('store');
        Route::get('/{teamMember}/edit', [EventTeamController::class, 'edit'])->name('edit');
        Route::patch('/{teamMember}', [EventTeamController::class, 'update'])->name('update');
        Route::delete('/{teamMember}', [EventTeamController::class, 'destroy'])->name('destroy');
    });
});