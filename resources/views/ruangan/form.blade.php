<x-app-layout>
    <x-slot name="title">{{ isset($ruangan) ? 'Edit Ruangan' : 'Tambah Ruangan' }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ isset($ruangan) ? route('ruangan.update', $ruangan) : route('ruangan.store') }}" method="POST" class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
            @csrf
            @if(isset($ruangan)) @method('PUT') @endif

            <div class="px-8 py-6 border-b border-white/50 bg-white/40">
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">
                    {{ isset($ruangan) ? 'Edit Ruangan' : 'Tambah Ruangan Baru' }}
                </h3>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-slate-700 mb-1">Kode Ruangan</label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $ruangan->kode ?? '') }}" placeholder="Contoh: R-101" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-slate-700 mb-1">Kapasitas Maks (Orang)</label>
                        <input type="number" id="kapasitas" name="kapasitas" min="1" value="{{ old('kapasitas', $ruangan->kapasitas ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Ruangan</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $ruangan->nama ?? '') }}" placeholder="Contoh: Lab Komputer Jaringan" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="gedung" class="block text-sm font-medium text-slate-700 mb-1">Gedung</label>
                        <input type="text" id="gedung" name="gedung" value="{{ old('gedung', $ruangan->gedung ?? '') }}" placeholder="Contoh: Gedung A" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                    <div>
                        <label for="lantai" class="block text-sm font-medium text-slate-700 mb-1">Lantai</label>
                        <input type="text" id="lantai" name="lantai" value="{{ old('lantai', $ruangan->lantai ?? '') }}" placeholder="Contoh: 1" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5">
                    </div>
                </div>

                <div>
                    <label for="fasilitas" class="block text-sm font-medium text-slate-700 mb-1">Daftar Fasilitas</label>
                    <textarea id="fasilitas" name="fasilitas" rows="3" placeholder="Contoh: Proyektor, AC, Papan Tulis" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5">{{ old('fasilitas', $ruangan->fasilitas_teks ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Pisahkan antar fasilitas dengan koma.</p>
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $ruangan->is_active ?? true)) class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 text-sm text-slate-700">Ruangan Aktif (Bisa dijadwalkan)</label>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-white/50 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('ruangan.index') }}" class="px-5 py-2 text-center border border-slate-300 bg-white text-slate-700 rounded-xl hover:bg-slate-50 font-medium">Batal</a>
                <button type="submit" class="px-5 py-2 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>