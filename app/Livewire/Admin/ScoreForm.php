<?php

namespace App\Livewire\Admin;

use App\Models\Group;
use App\Models\Score;
use Livewire\Component;

class ScoreForm extends Component
{
    public $token;
    public $group;
    public $event;
    public $participants = [];
    public $scores = [];

    public function mount($token)
    {
        $this->token = $token;
        $this->loadData();
    }

    public function loadData()
    {
        $this->group = Group::where('token', $this->token)
            ->with([
                'event',
                'pesertas.penyertaans.categorizable'
            ])
            ->firstOrFail();

        $this->event = $this->group->event;

        $this->participants = $this->group->pesertas->map(function ($peserta) {
      
            $penyertaan = $peserta->penyertaans
                ->where('event_id', $this->group->event_id)
                ->first();

            $category = $penyertaan?->categorizable?->name ?? 'Uncategorized';

            $existingScore = Score::where('group_id', $this->group->id)
                ->where('peserta_id', $peserta->id)
                ->where('event_id', $this->group->event_id)
                ->first();

            if (!isset($this->scores[$peserta->id])) {
                $this->scores[$peserta->id] = [
                    'round1' => $existingScore?->round1,
                    'round2' => $existingScore?->round2,
                    'round3' => $existingScore?->round3,
                    'remarks' => $existingScore?->remarks ?? '',
                ];
            }

            return [
                'id' => $peserta->id,
                'nama_penuh' => $peserta->nama_penuh,
                'category' => $category,
                'existing_score' => $existingScore,
            ];
        });
    }

    public function calculateAverage($participantId)
    {
        $scores = $this->scores[$participantId] ?? [];
        
        // Filter out empty values
        $rounds = array_filter([
            $scores['round1'] ?? null,
            $scores['round2'] ?? null,
            $scores['round3'] ?? null,
        ], fn($value) => $value !== null && $value !== '');

        if (empty($rounds)) {
            return 0;
        }

        return round(array_sum($rounds) / count($rounds), 2);
    }

    public function submit()
    {
        // Validate all scores
        $this->validate([
            'scores.*.round1' => 'nullable|numeric|min:0|max:100',
            'scores.*.round2' => 'nullable|numeric|min:0|max:100',
            'scores.*.round3' => 'nullable|numeric|min:0|max:100',
            'scores.*.remarks' => 'nullable|string|max:255',
        ]);

 
        foreach ($this->scores as $participantId => $scoreData) {

            if (empty($scoreData['round1']) && 
                empty($scoreData['round2']) && 
                empty($scoreData['round3'])) {
                continue;
            }

            // Update or create score record
            Score::updateOrCreate(
                [
                    'event_id' => $this->group->event_id,
                    'group_id' => $this->group->id,
                    'peserta_id' => $participantId,
                ],
                [
                    'round1' => $scoreData['round1'] ?: null,
                    'round2' => $scoreData['round2'] ?: null,
                    'round3' => $scoreData['round3'] ?: null,
                    'remarks' => $scoreData['remarks'] ?: null,
                ]
            );
        }

        session()->flash('success', 'Scores submitted successfully!');
        
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.score-form')
            ->layout('components.layouts.guest'); 
        
    }
}