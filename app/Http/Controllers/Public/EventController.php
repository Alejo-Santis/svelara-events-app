<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Display a listing of public events
     */
    public function index(Request $request): Response
    {
        $query = Event::with(['category', 'user'])
            ->where('is_published', true)
            ->where('is_public', true)
            ->where('status', 'published')
            ->where('start_date', '>', now());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('short_description', 'ILIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Price filter
        if ($request->filled('price_type')) {
            if ($request->price_type === 'free') {
                $query->where('is_free', true);
            } elseif ($request->price_type === 'paid') {
                $query->where('is_free', false);
            }
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Event type filter (online/presencial)
        if ($request->filled('event_type')) {
            if ($request->event_type === 'online') {
                $query->where('is_online', true);
            } elseif ($request->event_type === 'presencial') {
                $query->where('is_online', false);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to . ' 23:59:59');
        }

        // Date preset filters
        if ($request->filled('date_range')) {
            $now = now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('start_date', $now);
                    break;
                case 'tomorrow':
                    $query->whereDate('start_date', $now->addDay());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('start_date', $now->month)
                          ->whereYear('start_date', $now->year);
                    break;
                case 'next_month':
                    $nextMonth = $now->addMonth();
                    $query->whereMonth('start_date', $nextMonth->month)
                          ->whereYear('start_date', $nextMonth->year);
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'date_asc');
        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('start_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('start_date', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('start_date', 'asc');
        }

        $events = $query->paginate(12)->withQueryString();

        // Get categories with event count
        $categories = Category::withCount(['events' => function ($query) {
            $query->where('is_published', true)
                  ->where('is_public', true)
                  ->where('status', 'published')
                  ->where('start_date', '>', now());
        }])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        return Inertia::render('Public/Events/Index', [
            'events' => $events,
            'categories' => $categories,
            'filters' => $request->only([
                'search', 'category_id', 'price_type', 'min_price', 'max_price',
                'event_type', 'date_from', 'date_to', 'date_range', 'sort'
            ]),
            'total' => $query->count(),
        ]);
    }

    /**
     * Display the specified event
     */
    public function show(string $slug): Response
    {
        $event = Event::with(['category', 'user', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment views count
        $event->increment('views_count');

        // Check if user is registered
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = $event->attendees()
                ->where('user_id', Auth::id())
                ->whereIn('status', ['registered', 'attended'])
                ->exists();
        }

        // Check if can register (capacity available)
        $canRegister = true;
        if ($event->capacity) {
            $attendeesCount = $event->attendees()
                ->whereIn('status', ['registered', 'attended'])
                ->count();
            $canRegister = $attendeesCount < $event->capacity;
        }

        // Load attendees count
        $event->loadCount(['attendees' => function ($query) {
            $query->whereIn('status', ['registered', 'attended']);
        }]);

        // Get related events (same category, published, future, excluding current event)
        $relatedEvents = Event::with(['category', 'user'])
            ->where('is_published', true)
            ->where('is_public', true)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->where('id', '!=', $event->id)
            ->when($event->category_id, function ($query) use ($event) {
                $query->where('category_id', $event->category_id);
            })
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        return Inertia::render('Public/Events/Show', [
            'event' => $event,
            'canRegister' => $canRegister,
            'isRegistered' => $isRegistered,
            'relatedEvents' => $relatedEvents,
        ]);
    }

    /**
     * Register user to an event
     */
    public function register(string $slug): \Illuminate\Http\RedirectResponse
    {
        $event = Event::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if already registered
        $existingRegistration = $event->attendees()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['registered', 'attended'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Ya estás registrado en este evento.');
        }

        // Check capacity
        if ($event->capacity) {
            $attendeesCount = $event->attendees()
                ->whereIn('status', ['registered', 'attended'])
                ->count();

            if ($attendeesCount >= $event->capacity) {
                return back()->with('error', 'Este evento ha alcanzado su capacidad máxima.');
            }
        }

        // Create registration
        $event->attendees()->attach(Auth::id(), [
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return back()->with('success', '¡Te has registrado exitosamente al evento!');
    }

    /**
     * Cancel user registration from an event
     */
    public function cancelRegistration(string $slug): \Illuminate\Http\RedirectResponse
    {
        $event = Event::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $registration = $event->attendees()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['registered', 'attended'])
            ->first();

        if (!$registration) {
            return back()->with('error', 'No estás registrado en este evento.');
        }

        // Update status to cancelled instead of deleting
        $event->attendees()->updateExistingPivot(Auth::id(), [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Tu registro ha sido cancelado exitosamente.');
    }
}
