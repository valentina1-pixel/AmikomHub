<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // =========================
    // USER SIDE
    // =========================

    // Tampilkan semua events
    public function index()
    {
        $events = Event::with('category')
            ->latest()
            ->paginate(12);

        return view('events', compact('events'));
    }

    // Tampilkan detail event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        return view('event-detail', compact('event'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
    {
        return view('ticket');
    }


    // Hapus event
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}