<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(): Response
    {
        $user = Auth::user();

        // Get user's registered events
        $registeredEvents = $user->attendingEvents()
            ->with(['category', 'user'])
            ->whereIn('event_user.status', ['registered', 'attended'])
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        // Get recent tickets (past events attended)
        $recentTickets = $user->attendingEvents()
            ->with(['category', 'user'])
            ->whereIn('event_user.status', ['attended'])
            ->where('start_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get();

        // Calculate statistics
        $stats = [
            'registered_events' => $user->attendingEvents()
                ->whereIn('event_user.status', ['registered', 'attended'])
                ->where('start_date', '>', now())
                ->count(),
            'active_tickets' => $user->attendingEvents()
                ->whereIn('event_user.status', ['registered', 'attended'])
                ->count(),
            'attended_events' => $user->attendingEvents()
                ->where('event_user.status', 'attended')
                ->count(),
            'cancelled_events' => $user->attendingEvents()
                ->where('event_user.status', 'cancelled')
                ->count(),
        ];

        // Get recommended events (based on user's registered events categories)
        $userCategories = $user->attendingEvents()
            ->pluck('category_id')
            ->unique()
            ->toArray();

        $recommendedEvents = Event::with(['category', 'user'])
            ->where('is_published', true)
            ->where('is_public', true)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->when(count($userCategories) > 0, function ($query) use ($userCategories) {
                $query->whereIn('category_id', $userCategories);
            })
            ->whereNotIn('id', $user->attendingEvents()->pluck('events.id'))
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'upcomingEvents' => $registeredEvents,
            'recentTickets' => $recentTickets,
            'stats' => $stats,
            'recommendedEvents' => $recommendedEvents,
        ]);
    }
}
