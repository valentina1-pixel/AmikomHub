<?php

namespace App\Http\Controllers;
<<<<<<< HEAD

=======
>>>>>>> e004d3d80b3470af669529caa6b166a22cc2387c
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

<<<<<<< HEAD
=======

>>>>>>> e004d3d80b3470af669529caa6b166a22cc2387c
class HomeController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
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
=======
        // 1. Ambil semua jenis kategori untuk tampilan filter tab button 
        $categories = Category::all();

        // 2. Buat kueri dasar untuk mengambil event: 
        // - Gunakan Eager loading `category`
        // - Hanya tampilkan kegiatan dengan jadwal yang belum kedaluwarsa (>= hari ini)
        $query = Event::with('category')
                      ->where('date', '>=', now())
                      ->orderBy('date', 'asc');

        // 3. Filter query jika url memiliki parameter pencarian spesifik ?category=...
        if ($request->has('category') && $request->category != '') {
            // Saring berdasarkan relasi tabel rujukan melalui properti slug kategori.
            $query->whereHas('category', function ($q) use ($request) {
        $q->where('slug', $request->category);
                    });
                }

                // 4. Eksekusi query dan kirim data hasilnya ke template Blade
                $events = $query->get();

                return view('welcome', compact('events', 'categories'));
            }

}
>>>>>>> e004d3d80b3470af669529caa6b166a22cc2387c
