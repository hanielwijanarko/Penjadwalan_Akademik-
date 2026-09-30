<x-app-layout title="Klaster Keilmuan">
    <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden mt-4">
        <div class="px-6 py-5 border-b border-white/50 bg-white/40 flex flex-col sm:flex-row justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">Klaster Keilmuan</h3>
                <p class="mt-1 text-sm text-slate-600">Kelola master rumpun ilmu untuk Mata Kuliah dan Dosen.</p>
            </div>
            <a href="{{ route('klaster.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all">
                Tambah Klaster
            </a>
        </div>

        <div class="p-6">
            @if($klasters->isEmpty())
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-slate-800">Tidak ada Klaster</h3>
                    <p class="text-sm text-slate-500">Silakan tambahkan referensi klaster keilmuan baru.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Rumpun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/40 divide-y divide-slate-200">
                            @foreach($klasters as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">{{ $item->kode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $item->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500 hidden sm:table-cell max-w-xs truncate">{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('klaster.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1.5 rounded-md transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                            <form action="{{ route('klaster.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus klaster ini? Dosen dan Matkul mungkin terpengaruh.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $klasters->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
