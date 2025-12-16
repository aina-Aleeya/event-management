<?php

namespace App\Livewire\User;

use App\Models\Peserta;
use App\Models\Penyertaan;
use App\Models\User;
use App\Models\Event;
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
    public $eventCategories = [];

    public function mount($id)
    {
        $this->idIklan = $id;
        $this->event = Event::with(['categories', 'customCategories'])->find($id);

        // Combine default and custom categories
        $defaultCats = $this->event->categories->map(function($cat) {
            return ['id' => 'default_' . $cat->id, 'name' => $cat->name, 'type' => 'default'];
        });

        $customCats = $this->event->customCategories->map(function($cat) {
            return ['id' => 'custom_' . $cat->id, 'name' => $cat->name, 'type' => 'custom'];
        });

        $this->eventCategories = $defaultCats->merge($customCats)->toArray();

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
                'selected_categories' => [],
            ]
        ];
    }

    public function addPeserta(){
        

        $this->pesertas[] = [
            'nama_penuh' => '',
            'nama_panggilan' => '',
            'kelas' => '',
            'email' => '',
            'ic' => '',
            'jantina' => '',
            'tarikh_lahir' => '',
            'gambar' => null,
            'selected_categories' => [],
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
            'selected_categories' => [],
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
    
                    $this->pesertas[$index]['tarikh_lahir'] = $tarikh;
                    $this->pesertas[$index]['jantina'] = $jantina;
                }
            }
        }

    }

    public function save()
    {
        $groupToken = $this->groupToken ?? \Str::uuid()->toString();

        if (auth()->check()) {
            $pendaftarId = auth()->id();
        } else {
            // Kalau tak login → wajib isi maklumat pendaftar
            if (empty($this->pendaftar_nama) || empty($this->pendaftar_email)) {
                session()->flash('error', 'Please fill in registrant name and email.');
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
                session()->flash('error', 'Please fill in full name and MyKad number for all participants.');
                return;
            }
            if (empty($p['selected_categories'])) {
                session()->flash('error', 'Please select at least one category for each participant.');
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

            // Create penyertaan for each selected category
            foreach ($p['selected_categories'] as $categoryId) {
                // Parse: "default_1" or "custom_5"
                [$type, $id] = explode('_', $categoryId);

                // Determine the model class (CLEAR!)
                $categorizableType = $type === 'default' 
                    ? \App\Models\Category::class 
                    : \App\Models\CustomCategory::class;

                $existing = Penyertaan::where('event_id', $this->idIklan)
                    ->where('peserta_id', $peserta->id)
                    ->where('categorizable_id', $id)
                    ->where('categorizable_type', $categorizableType)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'group_token' => $groupToken,
                        'pendaftar_id' => $pendaftarId,
                        'status_bayaran' => 'pending',
                    ]);
                } else {
                    $lastEntry = Penyertaan::where('event_id', $this->idIklan)
                        ->where('categorizable_type', $categorizableType)
                        ->where('categorizable_id', $id)
                        ->orderByDesc('id')
                        ->first();

                    $number = $lastEntry ? intval($lastEntry->unique_id) + 1 : 1;

                    Penyertaan::create([
                        'event_id' => $this->idIklan,
                        'peserta_id' => $peserta->id,
                        'categorizable_id' => $id,                    // Just the ID: 1, 5, etc
                        'categorizable_type' => $categorizableType,   // Full class name
                        'unique_id' => str_pad($number, 4, '0', STR_PAD_LEFT),
                        'group_token' => $groupToken,
                        'status_bayaran' => 'pending',
                        'pendaftar_id' => $pendaftarId,
                    ]);
                }
            }
        }


        $this->dispatch('show-success', $this->idIklan);
    }


    public function render()
    {
        return view('livewire.user.peserta-form');
    }
}
