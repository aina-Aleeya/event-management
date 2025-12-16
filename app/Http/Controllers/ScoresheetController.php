<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ScoresheetController extends Controller
{
    /**
     * Export scoresheet for a specific group
     */
    public function exportGroup($eventId, $groupId)
    {
        $event = Event::findOrFail($eventId);
        $group = Group::with('pesertas')->findOrFail($groupId);

        // Get category for each participant
        foreach ($group->pesertas as $peserta) {
            $categoryData = \DB::table('penyertaan')
                ->leftJoin('categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                })
                ->leftJoin('custom_categories', function($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                         ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                })
                ->where('penyertaan.event_id', $eventId)
                ->where('penyertaan.peserta_id', $peserta->id)
                ->select(\DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                ->first();
            
            $peserta->category = $categoryData->category ?? 'Uncategorized';
        }

        $pdf = PDF::loadView('pdf.scoresheet-group', [
            'event' => $event,
            'group' => $group,
            'participants' => $group->pesertas
        ])->setPaper('a4', 'landscape');

        $filename = str_replace(' ', '_', $event->title) . '_' . str_replace(' ', '_', $group->name) . '_Scoresheet.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export scoresheets for all groups in one PDF
     */
    public function exportAllGroups(Request $request, $eventId)
    {
        $event = Event::with(['groups.pesertas'])->findOrFail($eventId);
        $category = $request->input('category');
        
        // Get all groups for this event
        $groups = $event->groups;
        
        // Process each group and its participants
        foreach ($groups as $group) {
            // Get category for each participant in the group
            foreach ($group->pesertas as $peserta) {
                $categoryData = \DB::table('penyertaan')
                    ->leftJoin('categories', function($join) {
                        $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                             ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                    })
                    ->leftJoin('custom_categories', function($join) {
                        $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                             ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                    })
                    ->where('penyertaan.event_id', $eventId)
                    ->where('penyertaan.peserta_id', $peserta->id)
                    ->select(\DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                    ->first();
                
                $peserta->category = $categoryData->category ?? 'Uncategorized';
            }
            
            // Filter participants by category if specified
            if ($category) {
                $group->filtered_pesertas = $group->pesertas->filter(function($peserta) use ($category) {
                    return $peserta->category === $category;
                });
            } else {
                $group->filtered_pesertas = $group->pesertas;
            }
        }
        
        // Filter out groups with no participants (if category filter is applied)
        if ($category) {
            $groups = $groups->filter(function($group) {
                return $group->filtered_pesertas->count() > 0;
            });
        }
        
        // Load the PDF view with all groups
        $pdf = PDF::loadView('pdf.scoresheet-all-groups', [
            'event' => $event,
            'groups' => $groups,
            'category' => $category
        ])->setPaper('a4', 'landscape');
        
        // Generate filename
        $filename = str_replace(' ', '_', $event->title) . '_All_Groups';
        if ($category) {
            $filename .= '_' . str_replace(' ', '_', $category);
        }
        $filename .= '_Scoresheet_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}