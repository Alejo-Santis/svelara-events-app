<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
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
            ->where('status', 'published');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_free')) {
            $query->where('is_free', $request->is_free);
        }

        if ($request->filled('is_online')) {
            $query->where('is_online', $request->is_online);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        // Default order by start date
        $query->orderBy('start_date', 'asc');

        $events = $query->paginate(12);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Public/Events/Index', [
            'events' => $events,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id', 'is_free', 'is_online', 'date_from', 'date_to']),
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
        if (auth()->check()) {
            $isRegistered = $event->attendees()
                ->where('user_id', auth()->id())
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

        return Inertia::render('Public/Events/Show', [
            'event' => $event,
            'canRegister' => $canRegister,
            'isRegistered' => $isRegistered,
        ]);
    }
}
