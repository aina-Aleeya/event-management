<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Penyertaan;

class PaymentForm extends Component
{
    public $groupToken;
    public $registrations;
    public $totalAmount = 0;

    public function mount()
    {
        $this->groupToken = request()->route('group_token');

        $this->registrations = Penyertaan::with('peserta', 'event')
            ->where('group_token', $this->groupToken)
            ->get();

        $fee = $this->registrations->first()?->event->entry_fee ?? 0;
        $this->totalAmount = $this->registrations->count() * $fee;
    }


    public function payNow()
    {
        foreach ($this->registrations as $reg) {
            $reg->update(['status_bayaran' => 'complete']);
        }
        session()->flash('success', 'Payment completed successfully!');
    }

    public function payLater()
    {
        foreach ($this->registrations as $reg) {
            $reg->update(['status_bayaran' => 'pending']);
        }
        session()->flash('success', 'Payment marked as pending. You can pay later.');
    }

    public function render()
    {
        return view('livewire.payment-form');
    }
}

