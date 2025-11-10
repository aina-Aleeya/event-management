<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Penyertaan;

class PaymentForm extends Component
{
    public $groupToken;
    public $registrations;
    public $totalAmount = 0;

    public function mount($group_token = null)
    {
        $this->groupToken = $group_token ?? request()->route('group_token');

        if (!$this->groupToken) {
            abort(404, 'Invalid payment link.');
        }

        $this->loadRegistrations();
    }

    public function loadRegistrations()
    {
        $this->registrations = Penyertaan::with('peserta', 'event')
            ->where('group_token', $this->groupToken)
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

    // public function addMember()
// {
//     // Redirect ke form peserta untuk tambah member baru
//     $eventId = $this->registrations->first()?->event_id;
//     if (!$eventId) {
//         return redirect()->route('home');
//     }

    //     return redirect()->route('peserta.form', [
//         'id' => $eventId, 
//         'group_token' => $this->groupToken
//     ]);
// }

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
