<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Penyertaan;
use App\Models\RankingReport;
use App\Models\Group;
use App\Models\Peserta;

class RankingReportPage extends Component
{
    public $eventId;
    public $category = 'Individu'; // 'Individu' or 'Berkumpulan'
    public $search = [];
    public $suggestions = [1 => [], 2 => [], 3 => []];
    public $ranking = [1 => null, 2 => null, 3 => null];
    public $rankingGroup = [1 => null, 2 => null, 3 => null];
    public $selectedNames = [];
    public $miniLeaderboard = [];

    public function mount($event)
    {
        $this->eventId = is_object($event) ? $event->id : $event;
        $this->loadMiniLeaderboard();
    }

    // ----------------------------
    // Triggered when user types
    // ----------------------------
    public function updatedSearch($value, $key)
    {
        $slot = (int) $key;
        $this->suggestions[$slot] = $this->getSuggestions($value);
    }

    // ----------------------------
    // Suggestion logic
    // ----------------------------
    private function getSuggestions($search)
    {
        if (!$search)
            return [];
    
        // Ambil penyertaan yang sudah menang supaya tak muncul lagi
        $alreadyRankedIds = RankingReport::where('event_id', $this->eventId)
            ->pluck('penyertaan_id')
            ->toArray();
    
        /*
        |--------------------------------------------------------------------------
        | 1) SUGGESTION UNTUK INDIVIDU
        | - Hanya ambil kategori bermula dengan 'I' (I..., IAF, IAM, IKB, etc.)
        |--------------------------------------------------------------------------
        */
        if ($this->category === 'Individu') {
    
            return Penyertaan::with('peserta')
                ->where('event_id', $this->eventId)
                ->whereNotIn('id', $alreadyRankedIds)
                ->where('kategori', 'like', 'I%')   // ❗ Filter utama: hanya kategori individu
                ->whereHas('peserta', function ($q) use ($search) {
                    $q->where('nama_penuh', 'like', "%{$search}%");
                })
                ->take(10)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->peserta->nama_penuh,
                ])
                ->toArray();
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | 2) SUGGESTION UNTUK BERKUMPULAN
        | - Cari group_token daripada peserta yang match nama
        | - Hanya ambil penyertaan kategori bermula 'G' (GAF, GAM, GKB, etc.)
        |--------------------------------------------------------------------------
        */
    
        // STEP A: Cari group berdasarkan nama group
        $groupsByName = Group::with('pesertas')
        ->where('event_id', $this->eventId)
        ->where('name', 'like', "%{$search}%")
        ->get();

        // STEP B: Cari group berdasarkan ahli group
        $groupsByMember = Group::with(['pesertas' => function ($q) use ($search) {
            $q->where('nama_penuh', 'like', "%{$search}%");
        }])
        ->where('event_id', $this->eventId)
        ->get()
        ->filter(fn($g) => $g->pesertas->isNotEmpty());

        // Gabungkan hasil keduanya + buang duplikat
        $groups = $groupsByName->merge($groupsByMember)->unique('id');

        if ($groups->isEmpty()) {
        return [];
        }

        $results = [];

        foreach ($groups as $group) {

        // Ambil semua ahli kumpulan (bukan yang match je)
        $allPesertas = $group->pesertas()->get();

        $namaAhli = $allPesertas->pluck('nama_penuh')->implode(', ');

        $results[] = [
            'id'   => $group->id,
            'name' => "{$group->name}: {$namaAhli}",
        ];
        }

        return array_slice($results, 0, 10);
    }
    

    public function selectSuggestion($id, $slot)
    {
        if ($this->category === 'Berkumpulan') {
            // $id adalah group_id
            $group = Group::with('pesertas') // ambil peserta melalui pivot
                ->find($id);
    
            if (!$group) return;
    
            // Ambil semua ahli peserta dalam group
            $names = $group->pesertas->pluck('nama_penuh')->implode(', ');
    
            $display = "{$group->name}: $names";
    
            // Simpan group_id dalam rankingGroup
            $this->rankingGroup[$slot] = $group->id;
        } else {
            // Individu
            $penyertaan = Penyertaan::with('peserta')->find($id);
            if (!$penyertaan) return;
    
            $display = $penyertaan->peserta->nama_penuh;
    
            // Simpan penyertaan_id dalam ranking
            $this->ranking[$slot] = $penyertaan->id;
        }
    
        // Update UI
        $this->selectedNames[$slot] = $display;
        $this->search[$slot] = $display;
        $this->suggestions[$slot] = [];
    }
    
    public function categoryChanged()
    {
        // Clear semua UI state
        $this->search = [];
        $this->suggestions = [1 => [], 2 => [], 3 => []];
        $this->ranking = [1 => null, 2 => null, 3 => null];
        $this->rankingGroup = [1 => null, 2 => null, 3 => null];
        $this->selectedNames = [];

        // Reload mini leaderboard ikut kategori baru
        $this->loadMiniLeaderboard();
    }

    public function save()
    {
        foreach ([1, 2, 3] as $slot) {
            if ($this->category === 'Berkumpulan') {
                $groupId = $this->rankingGroup[$slot];
                if (!$groupId) continue;

                // Ambil semua penyertaan ahli group
                $pesertas = Group::find($groupId)->pesertas;
                foreach ($pesertas as $p) {
                    RankingReport::updateOrCreate(
                        [
                            'event_id' => $this->eventId,
                            'penyertaan_id' => $p->id,
                        ],
                        ['ranking' => $slot]
                    );
                }
            } else { // Individu
                $penyertaanId = $this->ranking[$slot];
                if (!$penyertaanId) continue;

                RankingReport::updateOrCreate(
                    [
                        'event_id' => $this->eventId,
                        'penyertaan_id' => $penyertaanId,
                    ],
                    ['ranking' => $slot]
                );
            }
        }

        session()->flash('success', 'Ranking saved successfully!');
        $this->loadMiniLeaderboard();
    }



    public function resetRanking()
    {
        foreach ([1, 2, 3] as $slot) {
            $penyertaanId = $this->ranking[$slot];
            if ($penyertaanId) {
                RankingReport::where('event_id', $this->eventId)
                    ->where('penyertaan_id', $penyertaanId)
                    ->delete();
            }
            $this->ranking[$slot] = null;
            $this->selectedNames[$slot] = null;
            $this->search[$slot] = null;
        }

        session()->flash('success', 'Ranking reset successfully!');
        $this->loadMiniLeaderboard();
    }
    

    public function loadMiniLeaderboard()
{
    $reports = RankingReport::with('penyertaan.peserta')
        ->where('event_id', $this->eventId)
        ->orderBy('ranking')
        ->get();

    if ($this->category === 'Individu') {
        // Papar hanya individu
        $this->miniLeaderboard = $reports->filter(fn($r) => substr($r->penyertaan->kategori,0,1) === 'I')
            ->values();
    } else {
        $groupedReports = [];

        foreach ($reports as $report) {

            if (!str_starts_with($report->penyertaan->kategori, 'G')) continue;

            // Ambil peserta dari penyertaan
            $peserta = $report->penyertaan->peserta;

            if (!$peserta) continue;

            // Peserta mungkin ada lebih dari 1 group ikut event lain,
            // jadi kita tapis ikut event semasa
            $group = $peserta->groups()
                ->where('groups.event_id', $this->eventId)
                ->first();

            if (!$group) continue;

            // Kalau group belum dimasukkan, masukkan
            if (!isset($groupedReports[$group->id])) {

                $allMembers = $group->pesertas()->pluck('nama_penuh')->toArray();

                $groupedReports[$group->id] = (object)[
                    'ranking' => $report->ranking,
                    'group' => $group,
                    'namaAhli' => $allMembers,
                ];
            }
        }

        $this->miniLeaderboard = collect($groupedReports)->values();

    }
}


    public function render()
    {
        $event = Event::find($this->eventId);
        return view('livewire.ranking-report-page', ['event' => $event]);
    }
}