<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Peserta;

class AdminController extends Controller
{
    //
    public function dashboard(){
    
        $events = Event::select('id', 'title', 'click_count')->get();

        $participantSummary = \DB::table('penyertaan')
        ->join('events', 'penyertaan.event_id', '=', 'events.id')
        ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
        ->select('events.id as event_id', 'events.title', 'pesertas.category')
        ->selectRaw('COUNT(pesertas.id) as total')
        ->groupBy('events.id', 'events.title', 'pesertas.category')
        ->get();

        return view('admin.dashboard', compact('events', 'participantSummary'));
    }

    public function participants($eventId){
    
        $event = Event::with('peserta')
                    ->findOrFail($eventId);
        $participants = $event->peserta;
    

        return view('admin.participants', compact('event', 'participants'));
    }

    public function groups($eventId){
    
        $event = Event::with(['peserta' => function($query) {
            $query->with('user')->orderBy('id');
        }])->findOrFail($eventId);
    
        $groupedByCategory = $event->peserta->groupBy('category');
    
        $finalGroups = [];
        foreach ($groupedByCategory as $category => $participants) {
            $finalGroups[$category] = $participants->chunk(5);
        }

        return view('admin.groups', compact('event', 'finalGroups'));
    }
}