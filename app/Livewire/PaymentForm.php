<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penyertaan;

class PaymentForm extends Component
{
    public $groupToken;
    public $registrations;
    public $totalAmount = 0;
    public $eventId;

    public function mount($group_token = null, $event_id = null)
    {
        $this->groupToken = $group_token ?? request()->route('group_token') ?? session('guest_group_token');
        $this->eventId = $event_id ?? request()->route('event_id') ?? null;
        $this->loadRegistrations();
    }

    public function loadRegistrations()
    {
        $pendaftarId = auth()->check() ? auth()->id() : session('guest_id');

        $this->registrations = Penyertaan::with('peserta', 'event')
            ->where('pendaftar_id', $pendaftarId)
            ->where('status_bayaran', 'pending')
            ->when($this->eventId, fn($q) => $q->where('event_id', $this->eventId))
            ->get();

        if ($this->registrations->isEmpty()) {
            return redirect()->route('home'); 
        }

        $fee = $this->registrations->first()?->event->entry_fee ?? 0;
        $this->totalAmount = $this->registrations->count() * $fee;
    }

    public function payNow()
    {
        foreach ($this->registrations as $reg) {
            $reg->update(['status_bayaran' => 'complete']);
        }
        session()->flash('success', 'Payment completed successfully!');
        $this->loadRegistrations();
    }

    public function payLater()
    {
        foreach ($this->registrations as $reg) {
            $reg->update(['status_bayaran' => 'pending']);
        }
        session()->flash('success', 'Payment marked as pending. You can pay later.');
        $this->loadRegistrations();
    }

    public function addMember()
{
    $eventId = $this->registrations->first()?->event_id;
    if (!$eventId) {
        return redirect()->route('home');
    }

    // Gunakan group_token yang masih pending untuk batch yang sama
    $groupToken = $this->groupToken;

    return redirect()->route('peserta.form', [
        'id' => $eventId,
        'group_token' => $groupToken
    ]);
}


    public function deleteParticipant($id)
    {
        $p = Penyertaan::find($id);
        if ($p) {
            $p->delete();
        }

        $this->loadRegistrations(); 
        session()->flash('success', 'Participant removed successfully!');
    }

    public function render()
    {
        return view('livewire.payment-form');
    }
}
