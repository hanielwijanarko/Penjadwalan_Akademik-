<x-app-layout title="{{ isset($klaster) ? 'Edit Klaster' : 'Tambah Klaster' }}">
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-xl sm:rounded-3xl overflow-hidden">
            <div class="px-6 py-5 border-b border-white/50 bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-800">{{ isset($klaster) ? 'Ubah Data Klaster' : 'Tambah Klaster Baru' }}</h3>
            </div>

            <div class="p-6">
                <form action="{{ isset($klaster) ? route('klaster.update', $klaster) : route('klaster.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(isset($klaster)) @method('PUT') @endif

                    <div>
                        <label for="kode" class="block text-sm font-medium text-slate-700 mb-1">Kode Klaster <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $klaster->kode ?? '') }}" required placeholder="Contoh: RPL"
                            class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 transition-colors shadow-sm">
                        @error('kode') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Klaster / Rumpun <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $klaster->nama ?? '') }}" required placeholder="Contoh: Rekayasa Perangkat Lunak"
                            class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 transition-colors shadow-sm">
                        @error('nama') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 transition-colors shadow-sm">{{ old('deskripsi', $klaster->deskripsi ?? '') }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $klaster->is_active ?? true) ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-slate-300 bg-white text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 block text-sm text-slate-700 font-medium">Klaster Aktif / Tersedia</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('klaster.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-colors shadow-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl shadow-md transition-all">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
