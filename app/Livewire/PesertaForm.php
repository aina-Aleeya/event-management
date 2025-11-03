<?php

namespace App\Livewire;

use App\Models\Peserta;
use Livewire\Component;
use Livewire\WithFileUploads;

class PesertaForm extends Component
{
    use WithFileUploads;

    public $nama_penuh, $nama_panggilan, $kelas, $gambar, $email, $jantina, $ic, $tarikh_lahir;

    // public $searchNama = ''; // untuk auto-suggestion
    public $suggestions = [];

    public $idIklan;
    public $event;

    public function mount($id)
    {
        $this->idIklan = $id;
        $this->event = \App\Models\Event::find($id);
    }

    public function updatedNamaPenuh($value)
    {
        if (empty(trim($value))) {
            $this->resetFormFields();
            return; 
        }

        if (strlen($value) < 2) {
            $this->suggestions = [];
            return;
        }
        // $this->reset('suggestions');

        $this->suggestions = Peserta::where('nama_penuh', 'like', '%' . $this->nama_penuh . '%')
                            ->where('user_agent', request()->userAgent())
                            ->get();
    }

    public function updatedIc($value){
        $value = preg_replace('/\D/', '', $value);
        if (strlen($value) == 12) {
            $tahun = substr($value, 0, 2);
            $bulan = substr($value, 2, 2);
            $hari  = substr($value, 4, 2);

            $tahun_penuh = ($tahun < date('y')) ? '20' . $tahun : '19' . $tahun;
            $this->tarikh_lahir = sprintf('%04d-%02d-%02d', $tahun_penuh, $bulan, $hari);
            $jantina_digit = substr($value, -1);
            $this->jantina = ($jantina_digit % 2 == 0) ? 'Perempuan' : 'Lelaki';
        } else {
            $this->tarikh_lahir = null;
            $this->jantina = null;
        }
    }

    public function fillForm($id)
    {
        $peserta = Peserta::find($id);

        $this->nama_penuh = $peserta->nama_penuh;
        $this->nama_panggilan = $peserta->nama_panggilan;
        $this->kelas = $peserta->kelas;
        $this->email = $peserta->email;
        $this->jantina = $peserta->jantina;
        $this->ic = $peserta->ic;
        $this->tarikh_lahir = $peserta->tarikh_lahir;

        // $this->searchNama = '';
        $this->suggestions = [];
    }

    private function resetFormFields(){
        $this->nama_panggilan = '';
        $this->kelas = '';
        $this->email = '';
        $this->jantina = '';
        $this->ic = '';
        $this->tarikh_lahir = '';
        $this->gambar = null;
        $this->suggestions = [];
    }

    public function save()
    {
        $validated = $this->validate([
            'nama_penuh' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'jantina' => 'nullable|string',
            'ic' => 'nullable|string|max:20',
            'tarikh_lahir' => 'nullable|date',
            'gambar' => 'nullable|image|max:2048',

        ]);

        $validated['ip_address'] = request()->ip();
        $validated['user_agent'] = request()->userAgent();

        if ($this->gambar) {
            $validated['gambar'] = $this->gambar->store('peserta', 'public');
        } else {
            unset($validated['gambar']); 
        }

        //dd(request()->userAgent());

        $peserta = Peserta::updateOrCreate(['ic' => $this->ic], $validated);
        $peserta->events()->syncWithoutDetaching([$this->idIklan]);
        $this->dispatch('show-success', $this->idIklan);

        // session()->flash('message', 'Pendaftaran berjaya disimpan!');
        // $this->resetExcept('searchNama');
    }

    public function render()
    {
        return view('livewire.peserta-form');
    }
}