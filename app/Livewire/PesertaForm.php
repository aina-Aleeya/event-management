<?php

namespace App\Livewire;

use App\Models\Peserta;
use App\Models\Penyertaan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class PesertaForm extends Component
{
    use WithFileUploads;

    public $pesertas = [];
    public $suggestions = [];

    public $idIklan;
    public $event;
    public $pendaftar_nama;
    public $pendaftar_email;
    

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

    public function addPeserta(){
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

    public function removePeserta($index){
        unset($this->pesertas[$index]);
        $this->pesertas = array_values($this->pesertas);
    }

    public function updatedPesertas($value, $key)
    {
        // $key contoh: "0.nama_penuh"
        [$index, $field] = explode('.', $key);

        if ($field === 'nama_penuh' && strlen($value) >= 2) {
            $this->suggestions[$index] = Peserta::where('nama_penuh', 'like', "%{$value}%")
                ->where('user_agent', request()->userAgent())
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_penuh' => $item->nama_penuh,
                        'kelas' => $item->kelas,
                    ];
                })
                ->toArray();
        }else {
            // kosongkan suggestion kalau input < 2 huruf
            $this->suggestions[$index] = [];
        }
    }

    public function fillForm($id, $index)
    {
        $peserta = Peserta::find($id);
        if (!$peserta) return;

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

    private function resetFormFields($index){
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

    public function updated($propertyName, $value){
        if (str_contains($propertyName, 'pesertas.') && str_contains($propertyName, '.ic')) {
            // Dapatkan index
            preg_match('/pesertas\.(\d+)\.ic/', $propertyName, $matches);
            $index = $matches[1] ?? null;
    
            if ($index !== null) {
                $ic = preg_replace('/\D/', '', $value);
    
                if (strlen($ic) == 12) {
                    $tahun = substr($ic, 0, 2);
                    $bulan = substr($ic, 2, 2);
                    $hari  = substr($ic, 4, 2);
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

        $groupToken = $this->groupToken ?? \Str::uuid()->toString();

        if (auth()->check()) {
            $pendaftarId = auth()->id();
        } else {
            // Kalau tak login → wajib isi maklumat pendaftar
            if (empty($this->pendaftar_nama) || empty($this->pendaftar_email)) {
                session()->flash('error', 'Sila isi nama dan email pendaftar.');
                return;
            }
    
            // Cipta atau ambil guest user
            $guest = User::firstOrCreate(
                ['email' => $this->pendaftar_email],
                [
                    'name' => $this->pendaftar_nama,
                    'password' => Hash::make(Str::random(8)),
                    'role' => 'guest',
                ]
            );
    
            $pendaftarId = $guest->id;
            session(['guest_id' => $pendaftarId]);
        }

        $pending = Penyertaan::where('pendaftar_id', $pendaftarId)
            ->where('status_bayaran', 'pending')
            ->first();

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
                    // Kemas kini group token & pendaftar
                    $existing->update([
                        'group_token' => $groupToken,
                        'pendaftar_id' => $pendaftarId,
                        'status_bayaran' => 'pending',
                    ]);
        
                    // Tambah unique_id kalau belum ada
                    if (empty($existing->unique_id)) {
                        $lastEntry = Penyertaan::where('event_id', $this->idIklan)
                            ->where('kategori', $gabung)
                            ->orderByDesc('id')
                            ->first();
        
                        $number = $lastEntry ? intval(substr($lastEntry->unique_id, -4)) + 1 : 1;
                        $uniqueId = $gabung . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
                        $existing->update([
                            'kategori' => $gabung,
                            'unique_id' => $uniqueId,
                        ]);
                    }
        
                    $savedIds[] = $existing->id;
        
                } else {
                    // Cipta penyertaan baru
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
                        'pendaftar_id' => $pendaftarId,
                    ]);
        
                    $savedIds[] = $penyertaan->id;
                }
            }

        $this->dispatch('show-success', $this->idIklan);
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
