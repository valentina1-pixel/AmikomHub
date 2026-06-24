<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        // Pastikan model Event memiliki kolom 'stock'
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . strtoupper(Str::random(10));
        
        // 4. Hitung Harga (Harga Tiket + Biaya Admin)
        $adminFee = 5000;
        $totalPrice = $event->price + $adminFee;

        // 5. Merekam Transaksi ke Database
        Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
        ]);

        // 6. Kurangi stok tiket (Opsional, sesuaikan dengan alur bisnis Anda)
        $event->decrement('stock');

        // 7. Arahkan ke halaman sukses atau home dengan pesan
        return redirect()->route('home')->with('success', 'Pesanan berhasil dibuat! Kode pesanan Anda: ' . $orderId);
    }
}