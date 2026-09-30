<x-app-layout>
    <x-slot name="title">{{ isset($dosen) ? 'Edit Dosen' : 'Tambah Dosen' }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ isset($dosen) ? route('dosen.update', $dosen) : route('dosen.store') }}" method="POST" class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
            @csrf
            @if(isset($dosen)) @method('PUT') @endif

            <div class="px-8 py-6 border-b border-white/50 bg-white/40">
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">
                    {{ isset($dosen) ? 'Edit Dosen' : 'Tambah Profil Dosen Baru' }}
                </h3>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="nidn" class="block text-sm font-medium text-slate-700 mb-1">NIDN</label>
                        <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $dosen->email ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-slate-700 mb-1">No HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $dosen->no_hp ?? '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Afiliasi Klaster Keilmuan</label>
                    <div class="bg-white/50 border border-slate-300 rounded-xl p-4 space-y-2">
                        @foreach($klasters as $k)
                            <label class="flex items-center">
                                <input type="checkbox" name="klasters[]" value="{{ $k->id }}" 
                                       class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500"
                                       @checked(is_array(old('klasters', $dosenSelectedKlasters ?? [])) && in_array($k->id, old('klasters', $dosenSelectedKlasters ?? [])))>
                                <span class="ml-2 text-sm text-slate-700">{{ $k->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Pilih minimal satu klaster untuk menyesuaikan mata kuliah yang bisa diampu.</p>
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $dosen->is_active ?? true)) class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 text-sm text-slate-700">Dosen Aktif (Bisa dijadwalkan)</label>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-white/50 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('dosen.index') }}" class="px-5 py-2 text-center border border-slate-300 bg-white text-slate-700 rounded-xl hover:bg-slate-50 font-medium">Batal</a>
                <button type="submit" class="px-5 py-2 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>