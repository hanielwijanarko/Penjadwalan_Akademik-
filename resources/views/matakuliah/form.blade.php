<x-app-layout>
    <x-slot name="title">{{ isset($mataKuliah) ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ isset($mataKuliah) ? route('mata-kuliah.update', $mataKuliah) : route('mata-kuliah.store') }}" method="POST" class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
            @csrf
            @if(isset($mataKuliah)) @method('PUT') @endif

            <div class="px-8 py-6 border-b border-white/50 bg-white/40">
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">
                    {{ isset($mataKuliah) ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah Baru' }}
                </h3>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-slate-700 mb-1">Kode MK</label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $mataKuliah->kode ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                    <div>
                        <label for="sks" class="block text-sm font-medium text-slate-700 mb-1">SKS</label>
                        <input type="number" id="sks" name="sks" min="1" max="6" value="{{ old('sks', $mataKuliah->sks ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Mata Kuliah</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $mataKuliah->nama ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                </div>

                <div>
                    <label for="klaster_id" class="block text-sm font-medium text-slate-700 mb-1">Klaster Keilmuan</label>
                    <select id="klaster_id" name="klaster_id" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                        <option value="">-- Pilih Klaster --</option>
                        @foreach($klasters as $kla)
                            <option value="{{ $kla->id }}" @selected(old('klaster_id', $mataKuliah->klaster_id ?? '') == $kla->id)>{{ $kla->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5">{{ old('deskripsi', $mataKuliah->deskripsi ?? '') }}</textarea>
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $mataKuliah->is_active ?? true)) class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 text-sm text-slate-700">Mata Kuliah Aktif (Bisa dijadwalkan)</label>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-white/50 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('mata-kuliah.index') }}" class="px-5 py-2 text-center border border-slate-300 bg-white text-slate-700 rounded-xl hover:bg-slate-50 font-medium">Batal</a>
                <button type="submit" class="px-5 py-2 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>