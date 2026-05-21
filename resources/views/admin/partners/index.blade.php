@extends('layouts.admin')

@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Hubungkan dan atur kemitraan instansi / sponsor di sini.')

@section('content')
<div class="w-full">

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>Gagal menyimpan data. Pastikan format file berupa gambar (Max 2MB)!</span>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        
        <div class="px-8 py-6 bg-slate-50/50 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="font-black text-slate-700">Daftar Instansi / Partner</p>
                @if(isset($search) && $search)
                    <p class="text-xs text-slate-400 mt-1">
                        Menampilkan hasil pencarian untuk: <span class="font-bold text-indigo-600">"{{ $search }}"</span>
                    </p>
                @endif
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('admin.partners.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama partner..." 
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 font-medium transition bg-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    @if(isset($search) && $search)
                        <a href="{{ route('admin.partners.index') }}" 
                           class="px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl text-sm font-bold hover:bg-slate-200 transition text-center">
                            Reset
                        </a>
                    @endif
                </form>

                <button onclick="toggleModal('modalTambah')"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-md shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition flex items-center justify-center gap-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Partner
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Logo</th>
                        <th class="px-8 py-4">Nama Partner</th>
                        <th class="px-8 py-4">Bergabung Pada</th>
                        <th class="px-8 py-4 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($partners as $key => $partner)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6 font-bold text-slate-400">{{ $key + 1 }}</td>
                            <td class="px-8 py-6">
                                @if($partner->logo_url)
                                    <img src="{{ asset('storage/' . $partner->logo_url) }}" class="w-14 h-14 rounded-xl object-contain bg-slate-50 p-1 border shadow-sm">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs border border-dashed">
                                        No Logo
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 font-black text-slate-800">{{ $partner->name }}</td>
                            <td class="px-8 py-6 text-sm font-medium text-slate-400">
                                {{ $partner->created_at ? $partner->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex gap-2">
                                    <button onclick="openEditModal('{{ $partner->id }}', '{{ $partner->name }}')"
                                        class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition"
                                        title="Edit Partner">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus partner &quot;{{ $partner->name }}&quot;?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition"
                                            title="Hapus Partner">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center font-bold text-slate-400 bg-slate-50/10">
                                @if(isset($search) && $search)
                                    Tidak ditemukan partner dengan nama "{{ $search }}".
                                @else
                                    Belum ada data partner. Silakan tambahkan mitra baru!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden scale-95 transition-all transform duration-300">
        <div class="px-8 py-6 bg-slate-50/50 border-b flex justify-between items-center">
            <h3 class="text-xl font-black text-slate-800">Tambah Partner</h3>
            <button onclick="toggleModal('modalTambah')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="mb-5">
                <label class="block text-slate-700 font-bold mb-2 text-sm">Nama Partner / Instansi</label>
                <input type="text" name="name" required placeholder="Contoh: PT. Amikom Media, Pemkot..."
                    class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
            </div>
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2 text-sm">Upload Logo Partner</label>
                <input type="file" name="logo" accept="image/*"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition cursor-pointer">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="toggleModal('modalTambah')" class="px-5 py-3 bg-slate-100 text-slate-500 font-bold rounded-xl hover:bg-slate-200 transition">Batal</button>
                <button type="submit" class="px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden scale-95 transition-all transform duration-300">
        <div class="px-8 py-6 bg-slate-50/50 border-b flex justify-between items-center">
            <h3 class="text-xl font-black text-slate-800">Edit Partner</h3>
            <button onclick="toggleModal('modalEdit')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="formEditPartner" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block text-slate-700 font-bold mb-2 text-sm">Nama Partner Baru</label>
                <input type="text" id="inputEditNama" name="name" required placeholder="Masukkan nama instansi baru..."
                    class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
            </div>
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2 text-sm">Ganti Logo Partner (Opsional)</label>
                <input type="file" name="logo" accept="image/*"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition cursor-pointer">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="toggleModal('modalEdit')" class="px-5 py-3 bg-slate-100 text-slate-500 font-bold rounded-xl hover:bg-slate-200 transition">Batal</button>
                <button type="submit" class="px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        } else {
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    function openEditModal(id, currentName) {
        const form = document.getElementById('formEditPartner');
        const input = document.getElementById('inputEditNama');
        
        form.action = `/admin/partners/${id}`;
        input.value = currentName;
        
        toggleModal('modalEdit');
    }
</script>
@endsection