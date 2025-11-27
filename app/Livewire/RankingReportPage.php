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
    public $category = 'Individu';
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
    // Triggered when user types

    public function updatedSearch($value, $key)
    {
        $slot = (int) $key;

        // Bila user padam input
        if ($value === "" || $value === null) {

            // Reset suggestion & ranking slot
            $this->suggestions[$slot] = [];
            $this->ranking[$slot] = null;
            $this->rankingGroup[$slot] = null;
            $this->selectedNames[$slot] = null;

            // Reload mini leaderboard
            $this->loadMiniLeaderboard();
            return;
        }

        // Jika masih ada huruf → buat suggestion
        $this->suggestions[$slot] = $this->getSuggestions($value);
    }

    private function getSuggestions($search)
    {
        if (!$search)
            return [];

        // Ambil penyertaan yang sudah menang supaya tak muncul lagi
        $alreadyRankedIds = RankingReport::where('event_id', $this->eventId)
            ->pluck('penyertaan_id')
            ->toArray();

        /*
        | 1) SUGGESTION UNTUK INDIVIDU
        */
        if ($this->category === 'Individu') {

            return Penyertaan::with('peserta')
                ->where('event_id', $this->eventId)
                ->whereNotIn('id', $alreadyRankedIds)
                ->where('kategori', 'like', 'I%')
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
    
        //2) SUGGESTION UNTUK BERKUMPULAN
    
        // STEP A: Cari group berdasarkan nama group
        $groupsByName = Group::with('pesertas')
            ->where('event_id', $this->eventId)
            ->where('name', 'like', "%{$search}%")
            ->get();

        // STEP B: Cari group berdasarkan ahli group
        $groupsByMember = Group::with([
            'pesertas' => function ($q) use ($search) {
                $q->where('nama_penuh', 'like', "%{$search}%");
            }
        ])
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
                'id' => $group->id,
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

            if (!$group)
                return;

            // Ambil semua ahli peserta dalam group
            $names = $group->pesertas->pluck('nama_penuh')->implode(', ');

            $display = "{$group->name}: $names";

            // Simpan group_id dalam rankingGroup
            $this->rankingGroup[$slot] = $group->id;
        } else {
            // Individu
            $penyertaan = Penyertaan::with('peserta')->find($id);
            if (!$penyertaan)
                return;

            $display = $penyertaan->peserta->nama_penuh;

            // Simpan penyertaan_id dalam ranking
            $this->ranking[$slot] = $penyertaan->id;
        }

        // Update UI
        $this->selectedNames[$slot] = $display;
        $this->search[$slot] = $display;
        $this->suggestions[$slot] = [];
    }

    public function save()
    {
        foreach ([1, 2, 3] as $slot) {
            $penyertaanId = $this->ranking[$slot];
            if (!$penyertaanId)
                continue;

            RankingReport::updateOrCreate(
                [
                    'event_id' => $this->eventId,
                    'penyertaan_id' => $penyertaanId,
                ],
                ['ranking' => $slot]
            );
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

    public function changeCategory($category)
    {
        $this->category = $category;

        // Reset semua UI state
        $this->search = [];
        $this->suggestions = [1 => [], 2 => [], 3 => []];
        $this->ranking = [1 => null, 2 => null, 3 => null];
        $this->rankingGroup = [1 => null, 2 => null, 3 => null];
        $this->selectedNames = [];

        // Reload mini leaderboard
        $this->loadMiniLeaderboard();
    }
    public function loadMiniLeaderboard()
    {
        $reports = RankingReport::with('penyertaan.peserta')
            ->where('event_id', $this->eventId)
            ->orderBy('ranking')
            ->get();

        $this->miniLeaderboard = $reports->filter(function ($report) {
            if (!$report->penyertaan)
                return false;

            $prefix = substr($report->penyertaan->kategori, 0, 1);

            if ($this->category === 'Individu') {
                return $prefix === 'I';
            } else { // Berkumpulan
                return $prefix === 'G';
            }
        })->map(function ($report) {
            $prefix = substr($report->penyertaan->kategori, 0, 1);

            if ($prefix === 'G') {

                // 1) Cari rekod group peserta berdasarkan peserta_id dalam penyertaan
                $gp = \DB::table('group_peserta')
                    ->where('event_id', $this->eventId)
                    ->where('peserta_id', $report->penyertaan->peserta_id)
                    ->first();

                if ($gp) {
                    // 2) Ambil group dan ahli-ahlinya
                    $group = Group::with('pesertas')->find($gp->group_id);

                    if ($group) {
                        $report->group_name = $group->name;
                        $report->namaAhli = $group->pesertas->pluck('nama_penuh')->implode(', ');
                        $report->group = $group;
                    }
                }
            }

            return $report;
        })->values();
    }

    public function render()
    {
        $event = Event::find($this->eventId);
        return view('livewire.ranking-report-page', ['event' => $event]);
    }
}