<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Penyertaan;
use App\Models\RankingReport;

class RankingReportPage extends Component
{
    public $eventId;
    public $category;
    public $search = [];
    public $suggestions = [1 => [], 2 => [], 3 => []];
    public $ranking = [1 => null, 2 => null, 3 => null];
    public $selectedNames = [];
    public $miniLeaderboard = [];

    public function mount($event)
    {
        $this->eventId = is_object($event) ? $event->id : $event;
        $this->loadMiniLeaderboard();
    }

    public function updatedSearch($value, $key)
    {
        if (!$this->category) return;

        $slot = (int) $key;
        $this->suggestions[$slot] = $this->getSuggestions($value);
    }

    private function getSuggestions($search)
    {
        if (!$search) return [];

        $query = Penyertaan::with('peserta')
            ->where('event_id', $this->eventId);

        if ($this->category === 'Individu') {
            $query->whereHas('peserta', function ($q) use ($search) {
                $q->where('nama_penuh', 'like', "%$search%");
            });
        } else {
            $query->where('group_token', 'like', "%$search%");
        }

        $results = $query->take(10)->get();

        return $results->map(function ($p) {
            return [
                'id' => $p->peserta_id,
                'name' => $this->category === 'Individu'
                    ? ($p->peserta->nama_penuh ?? 'Unknown')
                    : "Group: " . ($p->group_token ?? 'Unknown'),
            ];
        })->toArray();
    }

    public function selectSuggestion($pesertaId, $slot)
    {
        $penyertaan = Penyertaan::with('peserta')
            ->where('event_id', $this->eventId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$penyertaan) return;

        $this->ranking[$slot] = $pesertaId;
        $this->selectedNames[$slot] = $this->category === 'Individu'
            ? ($penyertaan->peserta->nama_penuh ?? '')
            : "Group: " . ($penyertaan->group_token ?? '');

        $this->suggestions[$slot] = [];
        $this->search[$slot] = $this->selectedNames[$slot];
    }

public function save()
{
    foreach ([1, 2, 3] as $slot) {
        $pesertaId = $this->ranking[$slot];
        if (!$pesertaId) continue;

        // Cari penyertaan sebenar berdasarkan peserta & event
        $penyertaan = Penyertaan::where('event_id', $this->eventId)
                                ->where('peserta_id', $pesertaId)
                                ->first();

        if (!$penyertaan) continue;

        // Simpan ranking
        RankingReport::updateOrCreate(
            [
                'penyertaan_id' => $penyertaan->id, // guna penyertaan.id yang betul
                'event_id' => $this->eventId         // guna event sebenar
            ],
            ['ranking' => $slot]
        );
    }

    session()->flash('success', 'Ranking saved successfully!');
    $this->loadMiniLeaderboard();
}


    public function loadMiniLeaderboard()
    {
        $this->miniLeaderboard = RankingReport::with('penyertaan.peserta')
            ->whereHas('penyertaan', function ($q) {
                $q->where('event_id', $this->eventId);
            })
            ->orderBy('ranking')
            ->get();
    }

    public function render()
    {
        $event = Event::find($this->eventId);
        return view('livewire.ranking-report-page', ['event' => $event]);
    }
}
