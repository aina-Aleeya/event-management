<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Penyertaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentForm extends Component
{
    use WithFileUploads;

    public $groupToken;
    public $registrations;
    public $totalAmount = 0;
    public $eventId;
    public $showUploadForm = false;
    public $receiptImage;

    public function mount($group_token = null, $event_id = null)
    {
        $this->groupToken = $group_token ?? request()->route('group_token') ?? session('guest_group_token');
        $this->eventId = $event_id ?? request()->route('event_id');
        $this->loadRegistrations();
    }

    public function loadRegistrations()
    {
        $pendaftarId = Auth::check() ? Auth::id() : session('guest_id');
        
        $this->registrations = Penyertaan::with(['peserta', 'event', 'categorizable'])
            ->wherePendaftarId($pendaftarId)
            ->whereStatusBayaran('pending')
            ->when($this->eventId, fn($query) => $query->whereEventId($this->eventId))
            ->get();

        if ($this->registrations->isEmpty()) {
            return redirect()->route('home'); 
        }

        $fee = $this->registrations->first()->event->entry_fee ?? 0;
        $this->totalAmount = $this->registrations->count() * $fee;
    }

    public function payNow()
    {
        $this->showUploadForm = true;
    }

    public function backToParticipants()
    {
        $this->showUploadForm = false;
        $this->reset('receiptImage');
    }

    public function confirmPayment()
    {
        $this->validate([
            'receiptImage' => 'required|image|max:2048',
        ], [
            'receiptImage.required' => 'Please upload your payment receipt.',
            'receiptImage.image' => 'The file must be an image.',
            'receiptImage.max' => 'The image size must not exceed 2MB.',
        ]);

        DB::transaction(function () {
            $receiptPath = $this->receiptImage->store('payment-receipts', 'public');

            Penyertaan::whereIn('id', $this->registrations->pluck('id'))
                ->update([
                    'status_bayaran' => 'complete',
                    'payment_receipt' => $receiptPath,
                    'payment_date' => now(),
                ]);
        });

        session()->flash('success', 'Payment completed successfully!');

        return Auth::check() 
            ? redirect()->route('history.participant', ['eventId' => $this->eventId])
            : redirect()->route('dashboard');
    }

    public function payLater()
    {
        Penyertaan::whereIn('id', $this->registrations->pluck('id'))
            ->update(['status_bayaran' => 'pending']);

        session()->flash('success', 'Payment marked as pending. You can pay later.');
        
        return redirect()->route('dashboard');
    }

    public function addMember()
    {
        $eventId = $this->registrations->first()?->event_id;
        
        if (!$eventId) {
            return redirect()->route('home');
        }

        return redirect()->route('peserta.form', [
            'id' => $eventId,
            'group_token' => $this->groupToken
        ]);
    }

    public function deleteParticipant($id)
    {
        $penyertaan = Penyertaan::find($id);
        
        if ($penyertaan) {
            if ($penyertaan->payment_receipt) {
                Storage::disk('public')->delete($penyertaan->payment_receipt);
            }
            
            $penyertaan->delete();
            
            session()->flash('success', 'Participant removed successfully!');
        }

        $this->loadRegistrations();
    }

    public function render()
    {
        return view('livewire.user.payment-form');
    }
}