<x-app-layout>
    <x-slot name="title">Daftar Jadwal</x-slot>

    <div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
        <div class="px-6 py-5 border-b border-white/50 bg-white/40 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">Penjadwalan Kuliah</h3>
                <p class="mt-1 text-sm text-slate-600">{{ $semester ? $semester->nama . ' (' . $semester->tahun_ajaran . ')' : 'Belum ada semester aktif' }}</p>
            </div>
            
            @if($semester)
            <a href="{{ route('jadwal.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition-all">
                Tambah Jadwal
            </a>
            @endif
        </div>

        <div class="p-6">
            @if(!$semester || $jadwal->isEmpty())
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-slate-800">Belum ada jadwal kuliah</h3>
                    <p class="mt-1 text-sm text-slate-500">Mulai alokasikan matkul, dosen, dan ruangan kelas.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200/50">
                        <thead>
                            <tr>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Hari/Jam</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dosen</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Ruangan</th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                                <th class="relative px-3 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/50">
                            @foreach($jadwal as $item)
                            <tr class="hover:bg-white/40 transition-colors">
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-800 capitalize">{{ $item->hari }}</div>
                                    <div class="text-sm text-slate-500">{{ $item->rentang_jam }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="font-medium text-slate-800">{{ $item->mataKuliah->nama }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->mataKuliah->kode }} • {{ $item->mataKuliah->sks }} SKS</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-800">{{ $item->dosen->nama }}</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                                        {{ $item->ruangan->kode }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $item->kelas }} 
                                    @if($item->jumlah_mahasiswa > 0)
                                        <span class="text-xs text-slate-400">({{ $item->jumlah_mahasiswa }} Mhs)</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('jadwal.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('jadwal.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal ini?');">
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