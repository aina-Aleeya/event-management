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
use App\Http\Controllers\TeamMemberController;

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

// User-facing public routes
require __DIR__ . '/user.php';

// Home page
Route::get('/', function () {
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
})->name('dashboard');

// Score Submission (Public - anyone with token can submit)
Route::get('/markah/{token}', [MarkahController::class, 'form'])->name('markah.form');
Route::post('/markah/{token}', [MarkahController::class, 'submit'])->name('markah.submit');

// Event click tracking
Route::get('/ads/{id}/click', [EventController::class, 'trackClick'])->name('ads.click');

// Public invitation acceptance (anyone with token)
Route::get('team/invitation/{token}', [TeamMemberController::class, 'acceptInvitation'])
    ->name('team.invitation.accept');


// ============================================================================
// ADMIN ONLY ROUTES (Super Admin)
// ============================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // View specific organiser's dashboard
    Route::get('/organisers/{organiser}/dashboard', [AdminOrganiserController::class, 'viewDashboard'])
        ->name('organisers.dashboard');

    // Organiser Management (Admin only - CRUD operations)
    Route::resource('organisers', AdminOrganiserController::class);
});


// ============================================================================
// SHARED ROUTES (Admin + Organiser + Team Members with Permissions)
// Event Owners and Team Members can access based on RBAC
// ============================================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ----------------------------------------
    // Event Management (needs 'create_event' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:create_event'])->group(function () {
        Route::get('/create-event', CreateEvent::class)->name('create-event');
    });

    // ----------------------------------------
    // Event Dashboard (Event Owner or accepted team member only)
    // ----------------------------------------
    Route::middleware(['event.access'])->group(function () {
        Route::get('/events/{event}/dashboard', EventDashboardPage::class)->name('event.dashboard');
        Route::get('/events/{eventId}/edit', \App\Livewire\EditEvent::class)->name('event.edit');
    });

    // ----------------------------------------
    // Participant Management (needs 'view_participants' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:view_participants'])->group(function () {
        Route::get('/participants/{event}', [AdminController::class, 'participants'])->name('participants');
        Route::get('/participant/{peserta}', [AdminController::class, 'viewParticipant'])->name('participant.view');
        Route::get('/event/{event}/participants/export', [ParticipantExportController::class, 'export'])->name('event.participants.export');
        Route::get('/event/{event}/participants/pdf', [ParticipantExportController::class, 'exportParticipantsPdf'])->name('event.participants.pdf');
    });

    // ----------------------------------------
    // Grouping System (needs 'group_participants' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:group_participants'])->group(function () {
        Route::get('/grouping', [GroupController::class, 'groupingIndex'])->name('grouping.index');
        Route::get('/events/{event}/groups', [GroupController::class, 'groups'])->name('groups');
        Route::post('/events/{event}/groups/store', [GroupController::class, 'storeGroup'])->name('group.store');
        Route::post('/events/{event}/groups/auto', [GroupController::class, 'autoGroup'])->name('group.auto');
        Route::post('/events/{event}/assign', [GroupController::class, 'assignToGroup'])->name('group.assign');
        Route::post('/groups/{event}/move', [GroupController::class, 'moveParticipant'])->name('group.move');
        Route::post('/groups/{event}/remove', [GroupController::class, 'removeParticipant'])->name('group.remove');
        Route::get('/events/{event}/grouping/{category}', [GroupController::class, 'groupingByCategory'])->name('grouping.category');
        Route::get('/events/{event}/grouping', [GroupController::class, 'eventGrouping'])->name('event.grouping');
    });

    // ----------------------------------------
    // Reports & Rankings (needs 'view_participants' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:view_participants'])->group(function () {
        Route::get('/ranking-report/{event}', RankingReportPage::class)->name('ranking.report');
        Route::get('/event/{event}/leaderboard', LeaderboardPage::class)->name('event.leaderboard');
        Route::get('/event/{event}/ranking/export', [RankingExportController::class, 'export'])->name('event.ranking.export');
        Route::get('/ranking/{event}', [RankingController::class, 'show'])->name('ranking.show');
        Route::get('/ranking/{event}/export-sheet', [RankingExportController::class, 'exportSheet'])->name('ranking.export.sheet');
        Route::get('/ranking/{event}/export-pdf', [RankingExportController::class, 'exportPdf'])->name('ranking.export.pdf');
    });

    // ----------------------------------------
    // Scoresheet Management (needs 'manage_scoresheet' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:manage_scoresheet'])->group(function () {
        Route::get('/scoresheet/export-group/{event}/{group}', [ScoresheetController::class, 'exportGroup'])
            ->name('scoresheet.export-group');
        Route::get('/scoresheet/export-all-groups/{event}', [ScoresheetController::class, 'exportAllGroups'])
            ->name('scoresheet.export-all-groups');
    });

    // ----------------------------------------
    // Score Submission (needs 'submit_scores' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:submit_scores'])->group(function () {
        // Add any internal score management routes here
    });

    // ----------------------------------------
    // Certificate Management (needs 'manage_certificates' permission)
    // ----------------------------------------
    Route::middleware(['team.permission:manage_certificates'])->group(function () {
        Route::get('events/{event}/certificates', [CertificateController::class, 'managementPage'])
            ->name('certificate.manage');
        Route::put('events/{event}/certificate-settings', [CertificateController::class, 'updateSettings'])
            ->name('certificate.update-settings');
        Route::get('certificate/export-single/{event}/{peserta}', [CertificateController::class, 'exportSingle'])
            ->name('certificate.single');
        Route::get('certificate/export-group/{event}/{group}', [CertificateController::class, 'exportGroup'])
            ->name('certificate.group');
        Route::get('certificate/export-all/{event}', [CertificateController::class, 'exportAllParticipants'])
            ->name('certificate.all');
    });
});


// ============================================================================
// ORGANISER ROUTES (Event Owners Only)
// ============================================================================
Route::middleware(['auth', 'role:admin,organiser'])->prefix('organiser')->name('organiser.')->group(function () {
    
    // Organiser Dashboard
    Route::get('/dashboard', [OrganiserController::class, 'dashboard'])->name('dashboard');

    // ----------------------------------------
    // TEAM MEMBER MANAGEMENT
    // Only Event Owner can manage team members
    // No RBAC permission checks needed here
    // ----------------------------------------
    Route::prefix('events/{event}/team')->name('events.team.')->group(function () {
        Route::get('/', [TeamMemberController::class, 'index'])->name('index');
        Route::get('/create', [TeamMemberController::class, 'create'])->name('create');
        Route::post('/', [TeamMemberController::class, 'store'])->name('store');
        Route::patch('/{teamMember}/role', [TeamMemberController::class, 'updateRole'])->name('updateRole');
        Route::delete('/{teamMember}', [TeamMemberController::class, 'destroy'])->name('destroy');
        Route::post('/{teamMember}/resend', [TeamMemberController::class, 'resendInvitation'])->name('resend');
        Route::get('/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('edit');
        Route::put('/{teamMember}', [TeamMemberController::class, 'update'])->name('update');
    });
});