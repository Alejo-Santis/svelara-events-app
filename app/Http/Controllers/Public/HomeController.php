<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the landing page
     */
    public function index(): Response
    {
        // Get featured events (published, public, with available spots)
        $featuredEvents = Event::with(['category', 'user'])
            ->where('is_published', true)
            ->where('is_public', true)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('views_count', 'desc')
            ->limit(6)
            ->get();

        // Get upcoming events
        $upcomingEvents = Event::with(['category', 'user'])
            ->where('is_published', true)
            ->where('is_public', true)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        // Get categories with event count
        $categories = Category::withCount('events')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return Inertia::render('Public/Home', [
            'featuredEvents' => $featuredEvents,
            'upcomingEvents' => $upcomingEvents,
            'categories' => $categories,
        ]);
    }
}
