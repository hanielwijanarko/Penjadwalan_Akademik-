<x-app-layout>
    <x-slot name="title">Manajemen Ruangan</x-slot>

    <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/50 bg-white/40 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">Daftar Ruangan</h3>
                <p class="mt-1 text-sm text-slate-600">Kelola master ruang perkuliahan beserta kapasitasnya.</p>
            </div>
            <a href="{{ route('ruangan.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tambah Ruangan
            </a>
        </div>

        <div class="p-6">
            @if($ruangan->isEmpty())
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-slate-800">Tidak ada Ruangan</h3>
                    <p class="text-sm text-slate-500">Silakan tambahkan data ruangan baru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200/50">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Ruangan</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fasilitas</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="relative px-3 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/50">
                            @foreach($ruangan as $r)
                            <tr class="hover:bg-white/40 transition-colors">
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-slate-800">{{ $r->kode }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-slate-800">{{ $r->nama }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-slate-600">{{ $r->kapasitas }} Orang</td>
                                <td class="px-3 py-4 text-sm text-slate-600">{{ $r->fasilitas_teks ?: '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium text-xs {{ $r->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ $r->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('ruangan.edit', $r) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('ruangan.destroy', $r) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus ruangan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>