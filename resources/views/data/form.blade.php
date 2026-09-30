<x-app-layout title="{{ isset($dataItem) ? 'Edit Data' : 'Tambah Data' }}">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-sm rounded-2xl overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">
                    {{ isset($dataItem) ? 'Edit Data' : 'Tambah Data Baru' }}
                </h3>
            </div>
            
            <div class="p-6">
                <form action="{{ isset($dataItem) ? route('data.update', $dataItem) : route('data.store') }}" method="POST" class="space-y-5">
                    @csrf
                    @if(isset($dataItem)) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $dataItem->title ?? '') }}" required
                            class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 transition-colors shadow-sm">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/40 transition-colors shadow-sm">{{ old('description', $dataItem->description ?? '') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('data.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm shadow-indigo-200 transition-colors">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
