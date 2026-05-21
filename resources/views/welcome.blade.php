@extends('layouts.app')

@section('content')

    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1 Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.</h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.</p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">Mulai Jelajah</a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">Cara Pesan</a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <img src="{{ asset('assets/concert.png') }}" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">
        </div>
    </section>

    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
                <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-xl font-semibold text-sm bg-slate-100 text-slate-700 hover:bg-indigo-600 hover:text-white transition">Semua Kategori</a>
                @foreach($categories as $cat)
                    <a href="{{ url('/?category=' . $cat->slug) }}" class="px-5 py-2.5 rounded-xl font-semibold text-sm bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white transition">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="relative overflow-hidden aspect-[3/4]">
                        @if($event->poster)
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <img src="https://placehold.co/600x800" alt="No Image" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                            {{ $event->category->name ?? 'Tanpa Kategori' }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-slate-50 rounded-3xl border border-dashed text-slate-500">Belum ada event tersedia.</div>
            @endforelse
        </div>
    </section>

    <section class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 sm:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-black uppercase tracking-widest text-indigo-600 bg-indigo-100/70 px-4 py-1.5 rounded-full">Official Partners</span>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight mt-4">Didukung Oleh Instansi Terbaik</h2>
                <p class="text-slate-400 mt-2 text-sm">AmikomEventHub bekerja sama dengan berbagai korporasi, komunitas, dan instansi terpercaya.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8 items-center justify-center">
                @forelse($partners as $partner)
                    <div class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-slate-200 transition-all duration-300 group">
                        <div class="w-24 h-24 flex items-center justify-center mb-3">
                            @if($partner->logo_url)
                                <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="Logo {{ $partner->name }}" class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                            @else
                                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs">No Logo</div>
                            @endif
                        </div>
                        <p class="text-xs font-black text-slate-400 group-hover:text-slate-700 text-center transition-colors">{{ $partner->name }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 bg-white/50 rounded-2xl border border-dashed text-slate-400 font-medium text-sm">Belum ada partner pendukung.</div>
                @endforelse
            </div>
        </div>
    </section>

@endsection