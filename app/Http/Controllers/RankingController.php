<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    /**
     * Show rankings for specific event
     */
    public function show(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $selectedCategory = $request->input('category');

        // Get all scores for this event
        $scores = Score::where('event_id', $eventId)
            ->with(['peserta', 'group'])
            ->whereNotNull('average')
            ->get();

        // Get category for each score
        foreach ($scores as $score) {
            $categoryData = DB::table('penyertaan')
                ->leftJoin('categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                })
                ->leftJoin('custom_categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                })
                ->where('penyertaan.event_id', $eventId)
                ->where('penyertaan.peserta_id', $score->peserta_id)
                ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                ->first();
            
            $score->category = $categoryData->category ?? 'Uncategorized';
        }

        // Get unique categories
        $categories = $scores->pluck('category')->unique()->sort()->values();

        // Filter by category if selected
        if ($selectedCategory) {
            $scores = $scores->filter(function($score) use ($selectedCategory) {
                return $score->category === $selectedCategory;
            });
        }

        // Sort by average (descending) and add ranking
        $scores = $scores->sortByDesc('average')->values();
        
        // Add rank number
        foreach ($scores as $index => $score) {
            $score->rank = $index + 1;
        }

        return view('admin.ranking-show', [
            'event' => $event,
            'scores' => $scores,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory
        ]);
    }
}