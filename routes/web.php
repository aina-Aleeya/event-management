<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\EventDetails;
use App\Livewire\CreateEvent;
use App\Livewire\EventPage;
use App\Livewire\PesertaForm;
use App\Livewire\PaymentForm;
use App\Livewire\SenaraiPeserta;
use App\Livewire\RankingReportPage;
use App\Livewire\EventDashboardPage;
use App\Livewire\LeaderBoardPage;
use App\Livewire\HistoryPage;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\OrganiserReportController;

Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

Route::get('/events', EventPage::class)->name('events.page');

Route::get('/daftar/{id}', PesertaForm::class)->name('peserta.form');

Route::get('/events/{id}', EventDetails::class)->name('event.details');

Route::get('/ads/{id}/click', [EventController::class, 'trackClick'])->name('ads.click');

Route::get('/payment/{event_id}', PaymentForm::class)->name('payment.form');

Route::prefix('organiser')->name('organiser.')->group(function () {
    Route::get('/dashboard', [OrganiserController::class, 'dashboard'])->name('dashboard');
    Route::get('/participants/{event}', [OrganiserController::class, 'participants'])->name('participants');
    Route::get('/groups/{event}', [OrganiserController::class, 'groups'])->name('groups');
    Route::get('/participant/{peserta}', [OrganiserController::class, 'viewParticipant'])->name('participant.view');
    Route::get('/events/{event}/report', [OrganiserReportController::class, 'generate'])->name('events.report');
    Route::get('/ranking-report/{event}', RankingReportPage::class)->name('ranking.report');
    Route::get('/events/{event}/dashboard', EventDashboardPage::class)->name('event.dashboard');
    Route::get('/event/{event}/leaderboard', LeaderboardPage::class) ->name('event.leaderboard');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/history', HistoryPage::class)->name('history');
    Route::get('/history-participant/{eventId}', SenaraiPeserta::class)->name('history.participant');
    Route::get('/payment/{id}', action: PaymentForm::class)->name('payment.form');
   
    Route::get('/payment/{id}', action: PaymentForm::class)->name('payment.form');
    Route::get('/create-event', CreateEvent::class)->name('create-event');
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


