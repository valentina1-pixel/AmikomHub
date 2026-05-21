<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner; // <-- Menambahkan Model Partner untuk Soal 4
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua jenis kategori untuk tampilan filter tab button 
        $categories = Category::all();

        // 2. Buat kueri dasar untuk mengambil event: 
        // - Gunakan Eager loading `category`
        // - Hanya tampilkan kegiatan dengan jadwal yang belum kedaluwarsa (>= hari ini)
        $query = Event::with('category')
                    ->where('date', '>=', now()->toDateString()) // Filter agar tidak kedaluwarsa
                    ->orderBy('date', 'asc');

        // 3. Filter query jika url memiliki parameter pencarian spesifik ?category=...
        if ($request->has('category') && $request->category != '') {
            // Saring berdasarkan relasi tabel rujukan melalui properti slug kategori.
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query event
        $events = $query->get();

        // 5. AMBIL DATA PARTNER (Tambahan untuk Soal 4)
        // Mengambil semua data partner terbaru untuk dilempar ke halaman publik
        $partners = Partner::orderBy('created_at', 'desc')->get();

        // 6. Kirim semua variabel data hasilnya ke template Blade (welcome)
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}