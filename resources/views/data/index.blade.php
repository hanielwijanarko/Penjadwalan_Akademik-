<x-app-layout title="Modul Data">
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-sm rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    Modul Data
                    @if(!auth()->user()->isAdmin())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Baca Saja
                        </span>
                    @endif
                </h2>
                <p class="text-sm text-slate-500 mt-1">Daftar item data untuk demonstrasi RBAC.</p>
            </div>
            
            @if(auth()->user()->isAdmin())
                <a href="{{ route('data.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm shadow-indigo-200">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Data
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                        <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Deskripsi</th>
                        <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pembuat</th>
                        @if(auth()->user()->isAdmin())
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dataItems as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-6 font-medium text-slate-800">{{ $item->title }}</td>
                            <td class="py-3 px-6 text-slate-500 truncate max-w-xs hidden sm:table-cell">{{ str($item->description)->limit(50) }}</td>
                            <td class="py-3 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $item->creator?->name ?? 'System' }}
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="py-3 px-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('data.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('data.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Belum ada data tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($dataItems->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $dataItems->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
