@extends('layouts.app')

@section('title', 'Daftar Events')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Daftar Events</h1>
        <p class="text-gray-600">Temukan dan pesan event favorit Anda</p>
    </div>

    @if($events->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Event Image Placeholder -->
                    <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        @if($event->poster_path)
                            <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-white text-center">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <!-- Category Badge -->
                        @if($event->category)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full mb-2">
                                {{ $event->category->name }}
                            </span>
                        @endif

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $event->title }}</h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>

                        <!-- Event Details -->
                        <div class="space-y-2 mb-4 text-sm text-gray-600">
                            <!-- Date -->
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $event->date->format('d M Y, H:i') }}</span>
                            </div>

                            <!-- Location -->
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $event->location }}</span>
                            </div>

                            <!-- Stock -->
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $event->stock }} tiket tersedia</span>
                            </div>
                        </div>

                        <!-- Price and Button -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-gray-600 text-sm">Harga Mulai</p>
                                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('events.show', $event->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Events</h3>
            <p class="text-gray-600 mb-6">Saat ini belum ada event yang tersedia. Silahkan cek kembali nanti.</p>
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    @endif
</div>
@endsection
