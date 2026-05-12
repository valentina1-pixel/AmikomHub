<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori
        $categories = Category::all();

        // Ambil semua event + relasi category
        $query = Event::with('category')
                    ->orderBy('date', 'asc');

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {

            $query->whereHas('category', function ($q) use ($request) {

                $q->where('slug', $request->category);

            });

        }

        // Ambil data event
        $events = $query->get();

        // Kirim ke view
        return view('welcome', compact('events', 'categories'));
    }
}