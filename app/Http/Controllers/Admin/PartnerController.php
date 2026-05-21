<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    // READ: Menampilkan halaman utama daftar partner (Ditambah fitur Search Basic)
    public function index(Request $request)
    {
        // Tangkap input text dari form pencarian bernama 'search'
        $search = $request->input('search');

        // Menggunakan sintaks Eloquent untuk menyeleksi hasil ketika melakukan pencarian
        $partners = Partner::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->get();

        // Mengirimkan variabel partners dan keyword search ke dalam view
        return view('admin.partners.index', compact('partners', 'search'));
    }

    // CREATE: Menyimpan data partner baru beserta file logo
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Batas 2MB
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            // Menyimpan ke folder public/storage/partners
            $logoPath = $request->file('logo')->store('partners', 'public');
        }

        Partner::create([
            'name' => $request->name,
            'logo_url' => $logoPath
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner baru berhasil ditambahkan!');
    }

    // UPDATE: Memperbarui data nama partner dan mengganti logo lama jika ada logo baru
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $partner = Partner::findOrFail($id);
        $logoPath = $partner->logo_url;

        if ($request->hasFile('logo')) {
            // Hapus logo lama dari storage jika ada
            if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            // Simpan logo baru
            $logoPath = $request->file('logo')->store('partners', 'public');
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $logoPath
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil diperbarui!');
    }

    // DELETE: Menghapus data partner sekaligus file logonya dari penyimpanan
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo_url && Storage::disk('public')->exists($partner->logo_url)) {
            Storage::disk('public')->delete($partner->logo_url);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}