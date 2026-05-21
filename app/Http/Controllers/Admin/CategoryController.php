<?php

namespace App\Http\Controllers\Admin; // Perhatikan namespace-nya ada tambahan \Admin

use App\Http\Controllers\Controller; // PENTING: Harus di-import karena posisi controller ada di dalam sub-folder
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. READ: Menampilkan daftar kategori (Ditambah fitur Search Basic)
    public function index(Request $request)
    {
        // Tangkap input text dari form pencarian bernama 'search'
        $search = $request->input('search');

        // Menggunakan sintaks Eloquent untuk menyeleksi hasil ketika melakukan pencarian
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->get();

        // Mengarah ke folder resources/views/admin/categories/index.blade.php
        // Menyertakan variabel $search agar input pencarian lama tetap tampil di form
        return view('admin.categories.index', compact('categories', 'search'));
    }

    // 2. CREATE: Menyimpan kategori baru beserta slug
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. UPDATE: Mengubah nama kategori dan memperbarui slug
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. DELETE: Menghapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}