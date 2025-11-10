<?php

namespace App\Livewire;

use App\Models\Peserta;
use App\Models\Penyertaan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class PesertaForm extends Component
{
    use WithFileUploads;

    public $pesertas = [];
    public $suggestions = [];

    public $idIklan;
    public $event;

    public function mount($id)
    {
        $this->idIklan = $id;
        $this->event = \App\Models\Event::find($id);

        $this->pesertas = [
            [
                'nama_penuh' => '',
                'nama_panggilan' => '',
                'kelas' => '',
                'ic' => '',
                'tarikh_lahir' => '',
                'jantina' => '',
                'email' => '',
                'gambar' => null,
                'category' => '',
                'kategori' => '',
            ]
        ];
    }

    public function addPeserta()
    {
        if (count($this->pesertas) >= 6) {
            session()->flash('maxPeserta', 'Anda hanya boleh menambah maksimum 6 peserta sahaja.');
            return;
        }

        $this->pesertas[] = [
            'nama_penuh' => '',
            'nama_panggilan' => '',
            'kelas' => '',
            'email' => '',
            'ic' => '',
            'jantina' => '',
            'tarikh_lahir' => '',
            'gambar' => null,
            'category' => '',
            'kategori' => '',
        ];
    }

    public function removePeserta($index)
    {
        unset($this->pesertas[$index]);
        $this->pesertas = array_values($this->pesertas);
    }

    public function updatedPesertas($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'nama_penuh' && strlen($value) >= 2) {
            $this->suggestions[$index] = Peserta::where('nama_penuh', 'like', "%{$value}%")
                ->where('user_agent', request()->userAgent())
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_penuh' => $item->nama_penuh,
                        'kelas' => $item->kelas,
                    ];
                })
                ->toArray();
        } else {
            $this->suggestions[$index] = [];
        }
    }

    public function fillForm($id, $index)
    {
        $peserta = Peserta::find($id);
        if (!$peserta)
            return;

        $this->pesertas[$index] = [
            'nama_penuh' => $peserta->nama_penuh,
            'nama_panggilan' => $peserta->nama_panggilan,
            'kelas' => $peserta->kelas,
            'ic' => $peserta->ic,
            'tarikh_lahir' => $peserta->tarikh_lahir,
            'jantina' => $peserta->jantina,
            'email' => $peserta->email,
            'gambar' => null,
            'category' => '',
            'kategori' => $this->tentukanKategori($peserta->tarikh_lahir, $peserta->jantina),
        ];

        $this->suggestions[$index] = [];
    }

    private function resetFormFields($index)
    {
        $this->pesertas[$index] = [
            'nama_penuh' => '',
            'nama_panggilan' => '',
            'kelas' => '',
            'email' => '',
            'jantina' => '',
            'ic' => '',
            'tarikh_lahir' => '',
            'gambar' => null,
            'category' => '',
        ];

        $this->suggestions[$index] = [];
    }

    public function updated($propertyName, $value)
    {
        if (str_contains($propertyName, 'pesertas.') && str_contains($propertyName, '.ic')) {
            preg_match('/pesertas\.(\d+)\.ic/', $propertyName, $matches);
            $index = $matches[1] ?? null;

            if ($index !== null) {
                $ic = preg_replace('/\D/', '', $value);

                if (strlen($ic) == 12) {
                    $tahun = substr($ic, 0, 2);
                    $bulan = substr($ic, 2, 2);
                    $hari = substr($ic, 4, 2);
                    $tahun_penuh = ($tahun < date('y')) ? '20' . $tahun : '19' . $tahun;

                    $tarikh = sprintf('%04d-%02d-%02d', $tahun_penuh, $bulan, $hari);
                    $jantina_digit = substr($ic, -1);
                    $jantina = ($jantina_digit % 2 == 0) ? 'Perempuan' : 'Lelaki';
                    $kategori = $this->tentukanKategori($tarikh, $jantina);

                    $this->pesertas[$index]['tarikh_lahir'] = $tarikh;
                    $this->pesertas[$index]['jantina'] = $jantina;
                    $this->pesertas[$index]['kategori'] = $kategori;
                }
            }
        }
    }

    public function save()
    {
        $savedIds = [];


        $groupToken = \Str::uuid()->toString();

        foreach ($this->pesertas as $p) {

            if (empty($p['nama_penuh']) || empty($p['ic'])) {
                session()->flash('error', 'Sila isi nama penuh dan nombor IC untuk semua peserta.');
                return;
            }

            $validated = [
                'nama_penuh' => $p['nama_penuh'],
                'nama_panggilan' => $p['nama_panggilan'] ?? '',
                'kelas' => $p['kelas'] ?? '',
                'email' => $p['email'] ?? '',
                'jantina' => $p['jantina'] ?? '',
                'ic' => $p['ic'] ?? '',
                'tarikh_lahir' => $p['tarikh_lahir'] ?? '',
                'gambar' => null,
                'category' => $p['category'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            if (!empty($p['gambar']) && is_object($p['gambar'])) {
                $validated['gambar'] = $p['gambar']->store('peserta', 'public');
            } else {
                $existingPeserta = Peserta::where('ic', $p['ic'])->first();
                if ($existingPeserta && $existingPeserta->gambar) {
                    $validated['gambar'] = $existingPeserta->gambar;
                }
            }

            $peserta = Peserta::updateOrCreate(['ic' => $p['ic']], $validated);

            $kategoriUmur = $p['kategori']; // KB/KG/AM/AF/EL
            $categoryType = $p['category'] === 'Individu' ? 'I' : 'G';
            $gabung = $categoryType . $kategoriUmur; // Contoh: IAM / GAF

            $existing = Penyertaan::where('event_id', $this->idIklan)
                ->where('peserta_id', $peserta->id)
                ->first();

            if ($existing) {
                if (empty($existing->kategori) || empty($existing->unique_id)) {
                    $lastEntry = Penyertaan::where('event_id', $this->idIklan)
                        ->where('kategori', $gabung)
                        ->orderByDesc('id')
                        ->first();

                    $number = $lastEntry ? intval(substr($lastEntry->unique_id, -4)) + 1 : 1;
                    $uniqueId = $gabung . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

                    while (
                        Penyertaan::where('event_id', $this->idIklan)
                            ->where('unique_id', $uniqueId)
                            ->exists()
                    ) {
                        $number++;
                        $uniqueId = $gabung . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
                    }

                    $existing->kategori = $existing->kategori ?: $gabung;
                    $existing->unique_id = $existing->unique_id ?: $uniqueId;


                    $existing->group_token = $groupToken;

                    $existing->status_bayaran = 'pending';
                    $existing->save();
                }
                $savedIds[] = $existing->id;
            } else {
                $lastEntry = Penyertaan::where('event_id', $this->idIklan)
                    ->where('kategori', $gabung)
                    ->orderByDesc('id')
                    ->first();

                $number = $lastEntry ? intval(substr($lastEntry->unique_id, -4)) + 1 : 1;
                $uniqueId = $gabung . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

                $penyertaan = Penyertaan::create([
                    'event_id' => $this->idIklan,
                    'peserta_id' => $peserta->id,
                    'kategori' => $gabung,
                    'unique_id' => $uniqueId,

                    'group_token' => $groupToken,

                    'status_bayaran' => 'pending',
                ]);

                $savedIds[] = $penyertaan->id;
            }
        }

        $this->dispatch('show-success', $this->idIklan);


        return redirect()->route('payment.form', ['group_token' => $groupToken]);
    }



    private function tentukanKategori($tarikhLahir, $jantina)
    {
        $umur = Carbon::parse($tarikhLahir)->age;

        if ($umur <= 12) {
            return $jantina === 'Lelaki' ? 'KB' : 'KG';
        } elseif ($umur >= 60) {
            return 'EL';
        } else {
            return $jantina === 'Lelaki' ? 'AM' : 'AF';
        }
    }

    public function render()
    {
        return view('livewire.peserta-form');
    }
}
