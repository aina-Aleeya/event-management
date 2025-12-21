<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkahController extends Controller
{
    // Show form to submit scores
    public function form($token)
    {
        $group = Group::where('token', $token)
            ->with(['event', 'pesertas'])
            ->firstOrFail();

        // Get category for each participant
        foreach ($group->pesertas as $peserta) {
            $categoryData = DB::table('penyertaan')
                ->leftJoin('categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                })
                ->leftJoin('custom_categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                })
                ->where('penyertaan.event_id', $group->event_id)
                ->where('penyertaan.peserta_id', $peserta->id)
                ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                ->first();
            
            $peserta->category = $categoryData->category ?? 'Uncategorized';

            // Check if already submitted
            $peserta->existing_score = Score::where('group_id', $group->id)
                ->where('peserta_id', $peserta->id)
                ->first();
        }

        return view('admin.markah-form', [
            'group' => $group,
            'event' => $group->event,
            'participants' => $group->pesertas,
            'token' => $token
        ]);
    }

    // Process score submission
    public function submit(Request $request, $token)
    {
        $group = Group::where('token', $token)->firstOrFail();

        $request->validate([
            'scores' => 'required|array',
            'scores.*.peserta_id' => 'required|exists:pesertas,id',
            'scores.*.round1' => 'nullable|numeric|min:0',
            'scores.*.round2' => 'nullable|numeric|min:0',
            'scores.*.round3' => 'nullable|numeric|min:0',
            'scores.*.remarks' => 'nullable|string|max:255',
        ]);

        // Save scores
        foreach ($request->scores as $scoreData) {
            // Skip if all rounds empty
            if (empty($scoreData['round1']) && empty($scoreData['round2']) && empty($scoreData['round3'])) {
                continue;
            }

            Score::updateOrCreate(
                [
                    'event_id' => $group->event_id,
                    'group_id' => $group->id,
                    'peserta_id' => $scoreData['peserta_id'],
                ],
                [
                    'round1' => $scoreData['round1'] ?? null,
                    'round2' => $scoreData['round2'] ?? null,
                    'round3' => $scoreData['round3'] ?? null,
                    'remarks' => $scoreData['remarks'] ?? null,
                ]
            );
        }

        // Redirect back to form with success message
        return redirect()->route('markah.form', ['token' => $token])
            ->with('success', 'Scores submitted successfully!');
    }
}