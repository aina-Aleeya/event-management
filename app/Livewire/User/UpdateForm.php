<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Peserta;

class UpdateForm extends Component
{
    public $pesertas = [];
    public $peserta_id;
    public $eventId;


    public function mount($eventId,$peserta_id)
    {
        $this->eventId = $eventId;
        $this->peserta_id = $peserta_id;

        $peserta = Peserta::findOrFail($peserta_id);

        $this->pesertas = [
            [
                'nama_penuh' => $peserta->nama_penuh,
                'nama_panggilan' => $peserta->nama_panggilan,
                'kelas' => $peserta->kelas,
                'ic' => $peserta->ic,
                'tarikh_lahir' => $peserta->tarikh_lahir,
                'jantina' => $peserta->jantina,
                'email' => $peserta->email,
                'gambar' => null,

            ]
        ];
    }

    public function updatedPesertas($value, $key)
    {
        if (!str_ends_with($key, '.ic'))
            return;

        preg_match('/(\d+)\.ic$/', $key, $matches);
        $index = $matches[1] ?? null;

        if ($index === null)
            return;

        $this->handleMykad($value, $index);
    }

    public function handleMykad($value, $index)
    {
        $ic = preg_replace('/\D/', '', $value);

        if (strlen($ic) !== 12)
            return;

        $tahun = substr($ic, 0, 2);
        $bulan = substr($ic, 2, 2);
        $hari = substr($ic, 4, 2);

        $tahun_penuh = ($tahun < date('y')) ? '20' . $tahun : '19' . $tahun;

        $this->pesertas[$index]['tarikh_lahir']
            = sprintf('%04d-%02d-%02d', $tahun_penuh, $bulan, $hari);

        $jantina_digit = substr($ic, -1);
        $this->pesertas[$index]['jantina']
            = ($jantina_digit % 2 === 0) ? 'Perempuan' : 'Lelaki';
    }


    public function save()
    {
        $data = $this->pesertas[0];

        Peserta::where('id', $this->peserta_id)->update([
            'nama_penuh' => $data['nama_penuh'],
            'nama_panggilan' => $data['nama_panggilan'],
            'kelas' => $data['kelas'],
            'ic' => $data['ic'],
            'tarikh_lahir' => $data['tarikh_lahir'],
            'jantina' => $data['jantina'],
            'email' => $data['email'],
        ]);

        $this->dispatch('show-success', ['eventId' => $this->eventId]);
    }


    public function render()
    {
        return view('livewire.user.update-form');
    }

}