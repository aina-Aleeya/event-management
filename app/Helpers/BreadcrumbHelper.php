<?php

namespace App\Helpers;

use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Support\Facades\Route;

class BreadcrumbHelper
{
    /**
     * Generate breadcrumbs based on current route
     */
    public static function generate()
    {
        $routeName = Route::currentRouteName();
        $parameters = Route::current()->parameters();

        $breadcrumbs = [];

        // Map route names to breadcrumb configurations
        switch ($routeName) {
            // Dashboard
            case 'admin.dashboard':
                // No additional breadcrumbs - just shows "Dashboard"
                break;

            // Create Event
            case 'admin.create-event':
                $breadcrumbs[] = ['label' => 'Create Event'];
                break;

            // Event Dashboard
            case 'admin.event.dashboard':
                $eventParam = $parameters['event'] ?? null;
                // Handle both ID and model instance
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Events',
                        'url' => route('admin.dashboard')
                    ];
                    $breadcrumbs[] = ['label' => $event->title];
                }
                break;

            // Participants List
            case 'admin.participants':
                $eventParam = $parameters['event'] ?? null;
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Events',
                        'url' => route('admin.dashboard')
                    ];
                    $breadcrumbs[] = [
                        'label' => $event->title,
                        'url' => route('admin.event.dashboard', $event->id)
                    ];
                    $breadcrumbs[] = ['label' => 'Participants'];
                }
                break;

            // Event Dashboard
            case 'admin.ranking.show':
                $eventParam = $parameters['event'] ?? null;
                // Handle both ID and model instance
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Events',
                        'url' => route('admin.dashboard')
                    ];
                    $breadcrumbs[] = [
                        'label' => $event->title,
                        'url' => route('admin.event.dashboard', $event->id)
                    ];
                    $breadcrumbs[] = ['label' => 'View Ranking'];
                }
                break;


            // Participant Details
            case 'admin.participant.view':
                $pesertaParam = $parameters['peserta'] ?? null;
                $pesertaId = $pesertaParam instanceof Peserta ? $pesertaParam->id : $pesertaParam;
                $peserta = $pesertaId ? Peserta::find($pesertaId) : null;

                if ($peserta) {
                    // Try to get the event from referrer or session
                    $eventId = session('last_event_id');
                    if ($eventId) {
                        $event = Event::find($eventId);
                        if ($event) {
                            $breadcrumbs[] = [
                                'label' => 'Events',
                                'url' => route('admin.dashboard')
                            ];
                            $breadcrumbs[] = [
                                'label' => $event->title,
                                'url' => route('admin.event.dashboard', $event->id)
                            ];
                            $breadcrumbs[] = [
                                'label' => 'Participants',
                                'url' => route('admin.participants', $event->id)
                            ];
                        }
                    }
                    $breadcrumbs[] = ['label' => $peserta->nama_penuh];
                }
                break;

            // Grouping Index
            case 'admin.grouping.index':
                $breadcrumbs[] = ['label' => 'Grouping System'];
                break;

            // Groups by Event
            case 'admin.groups':
                $eventParam = $parameters['event'] ?? null;
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;
                $category = request('category');

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Grouping System',
                        'url' => route('admin.grouping.index')
                    ];
                    $breadcrumbs[] = [
                        'label' => $event->title,
                        'url' => route('admin.groups', $event->id)
                    ];

                    if ($category) {
                        $breadcrumbs[] = ['label' => $category];
                    }
                }
                break;

            // Ranking Report
            case 'admin.ranking.report':
                $eventParam = $parameters['event'] ?? null;
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Events',
                        'url' => route('admin.dashboard')
                    ];
                    $breadcrumbs[] = [
                        'label' => $event->title,
                        'url' => route('admin.event.dashboard', $event->id)
                    ];
                    $breadcrumbs[] = ['label' => 'Ranking Report'];
                }
                break;

            // Leaderboard
            case 'admin.event.leaderboard':
                $eventParam = $parameters['event'] ?? null;
                $eventId = $eventParam instanceof Event ? $eventParam->id : $eventParam;
                $event = $eventId ? Event::find($eventId) : null;

                if ($event) {
                    $breadcrumbs[] = [
                        'label' => 'Events',
                        'url' => route('admin.dashboard')
                    ];
                    $breadcrumbs[] = [
                        'label' => $event->title,
                        'url' => route('admin.event.dashboard', $event->id)
                    ];
                    $breadcrumbs[] = ['label' => 'Leaderboard'];
                }
                break;

            default:
                // No breadcrumbs for unknown routes
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Render breadcrumbs HTML
     */
    public static function render()
    {
        $breadcrumbs = self::generate();

        if (empty($breadcrumbs)) {
            return '';
        }

        $html = '';
        foreach ($breadcrumbs as $breadcrumb) {
            $html .= '<li class="flex items-center">';
            $html .= '<svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
            $html .= '</svg>';

            if (isset($breadcrumb['url'])) {
                $html .= '<a href="' . $breadcrumb['url'] . '" class="hover:text-purple-600 transition">';
                $html .= e($breadcrumb['label']);
                $html .= '</a>';
            } else {
                $html .= '<span class="text-gray-900 font-medium">' . e($breadcrumb['label']) . '</span>';
            }

            $html .= '</li>';
        }

        return $html;
    }
}