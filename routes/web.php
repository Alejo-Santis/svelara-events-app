<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('eventos')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');

    // Event registration (requires authentication)
    Route::middleware('auth')->group(function () {
        Route::post('/{slug}/registrar', [EventController::class, 'register'])->name('register');
        Route::delete('/{slug}/cancelar', [EventController::class, 'cancelRegistration'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index', [
            'upcomingEvents' => [],
            'recentTickets' => [],
            'stats' => [
                'registered_events' => 0,
                'active_tickets' => 0,
                'attended_events' => 0,
            ]
        ]);
    })->name('dashboard');
});
