<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\User\EventPage;
use App\Livewire\User\EventDetails;
use App\Livewire\User\PesertaForm;
use App\Livewire\User\PaymentForm;
use App\Livewire\User\SenaraiPeserta;
use App\Livewire\User\HistoryPage;
use App\Http\Controllers\CertificateController;


Route::get('/events', EventPage::class)->name('events.page');


Route::get('/daftar/{id}', PesertaForm::class)->name('peserta.form');

Route::get('/events/{id}', EventDetails::class)->name('event.details');

Route::get('/payment/{event_id}', PaymentForm::class)->name('payment.form');

Route::middleware(['auth'])->group(function () {

    Route::get('/history', HistoryPage::class)->name('history');

    Route::get('/history-participant/{eventId}', SenaraiPeserta::class)->name('history.participant');
   
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

Route::prefix('events')->name('user.')->group(function () {
    // Certificate Download Page
    Route::get('{event}/certificates', [CertificateController::class, 'participantCertificates'])
        ->name('certificates');
    
    // Download Certificate (POST with email)
    Route::post('{event}/certificate/download', [CertificateController::class, 'downloadParticipantCertificate'])
        ->name('certificate.download');
});