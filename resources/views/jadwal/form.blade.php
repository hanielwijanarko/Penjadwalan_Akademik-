<x-app-layout>
    <x-slot name="title">{{ isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ isset($jadwal) ? route('jadwal.update', $jadwal) : route('jadwal.store') }}" method="POST" class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl sm:rounded-3xl overflow-hidden">
            @csrf
            @if(isset($jadwal)) @method('PUT') @endif

            <div class="px-8 py-6 border-b border-white/50 bg-white/40">
                <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">
                    {{ isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}
                </h3>
            </div>

            <div class="p-8 space-y-6">
                <!-- Data Master -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-200/50">
                    <div>
                        <label for="mata_kuliah_id" class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
                        <select id="mata_kuliah_id" name="mata_kuliah_id" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5 backdrop-blur-sm" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($mataKuliah as $mk)
                                <option value="{{ $mk->id }}" @selected(old('mata_kuliah_id', $jadwal->mata_kuliah_id ?? '') == $mk->id)>
                                    {{ $mk->kode }} - {{ $mk->nama }} ({{ $mk->sks }} SKS)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kelas" class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                            <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $jadwal->kelas ?? '') }}" placeholder="Contoh: A" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                        </div>
                        <div>
                            <label for="jumlah_mahasiswa" class="block text-sm font-medium text-slate-700 mb-1">Jml Mahasiswa</label>
                            <input type="number" min="0" id="jumlah_mahasiswa" name="jumlah_mahasiswa" value="{{ old('jumlah_mahasiswa', $jadwal->jumlah_mahasiswa ?? '') }}" placeholder="Opsional" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5">
                        </div>
                    </div>
                </div>

                <!-- Waktu Terjadwal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-200/50">
                    <div>
                        <label for="hari" class="block text-sm font-medium text-slate-700 mb-1">Hari</label>
                        <select id="hari" name="hari" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach(\App\Enums\Hari::options() as $val => $label)
                                <option value="{{ $val }}" @selected(old('hari', $jadwal->hari ?? '') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', isset($jadwal) ? $jadwal->jam_mulai_pendek : '') }}" class="w-full bg-white/50 border border-slate-300 rounded-xl p-2.5" required>
                    </div>
                </div>

                <!-- Dinamis: Tergantung Matkul, Hari & Jam -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-200/50">
                    <div>
                        <label for="dosen_id" class="block text-sm font-medium text-slate-700 mb-1">Dosen Pengampu</label>
                        <select id="dosen_id" name="dosen_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5" required data-selected="{{ old('dosen_id', $jadwal->dosen_id ?? '') }}">
                            <option value="">-- Lengkapi Matkul, Hari & Jam --</option>
                        </select>
                    </div>

                    <div>
                        <label for="ruangan_id" class="block text-sm font-medium text-slate-700 mb-1">Ruangan</label>
                        <select id="ruangan_id" name="ruangan_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5" required data-selected="{{ old('ruangan_id', $jadwal->ruangan_id ?? '') }}">
                            <option value="">-- Lengkapi Matkul, Hari & Jam --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 border-t border-white/50 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('jadwal.index') }}" class="px-5 py-2 min-w-[100px] text-center border border-slate-300 bg-white text-slate-700 rounded-xl hover:bg-slate-50 font-medium">Batal</a>
                <button type="submit" class="px-5 py-2 min-w-[100px] border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 font-medium">
                    Simpan
                </button>
            </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elMatkul = document.getElementById('mata_kuliah_id');
            const elHari = document.getElementById('hari');
            const elJam = document.getElementById('jam_mulai');
            const elDosen = document.getElementById('dosen_id');
            const elRuangan = document.getElementById('ruangan_id');

            const abaikanJadwalId = '{{ $jadwal->id ?? '' }}';

            function loadOptions(type) {
                const mkId = elMatkul.value;
                const hari = elHari.value;
                const jam = elJam.value;

                if (!mkId || !hari || !jam) {
                    if (type === 'dosen') resetSelect(elDosen, '-- Lengkapi Matkul, Hari & Jam --');
                    if (type === 'ruangan') resetSelect(elRuangan, '-- Lengkapi Matkul, Hari & Jam --');
                    return;
                }

                const endpoint = type === 'dosen' ? '{{ route("api.jadwal-dropdown.dosen") }}' : '{{ route("api.jadwal-dropdown.ruangan") }}';
                const currentSelect = type === 'dosen' ? elDosen : elRuangan;
                const preSelected = currentSelect.dataset.selected;

                currentSelect.disabled = true;
                currentSelect.innerHTML = `<option value="">Memuat...</option>`;

                const params = new URLSearchParams({ mata_kuliah_id: mkId, hari: hari, jam_mulai: jam });
                if (abaikanJadwalId) params.append('abaikan_jadwal_id', abaikanJadwalId);

                fetch(`${endpoint}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    currentSelect.innerHTML = '<option value="">-- Pilih --</option>';
                    if (data.length === 0) {
                        currentSelect.innerHTML = '<option value="">-- Tidak Ada/Bentrok --</option>';
                    } else {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = type === 'dosen' ? item.nama : `${item.kode} (${item.nama} - Kapasitas: ${item.kapasitas})`;
                            if (preSelected && item.id.toString() === preSelected.toString()) {
                                option.selected = true;
                            }
                            currentSelect.appendChild(option);
                        });
                    }
                })
                .catch(() => resetSelect(currentSelect, '-- Gagal Memuat --'))
                .finally(() => {
                    currentSelect.disabled = false;
                    currentSelect.dataset.selected = '';
                });
            }

            function resetSelect(el, msg) {
                el.innerHTML = `<option value="">${msg}</option>`;
                el.disabled = false;
            }

            [elMatkul, elHari, elJam].forEach(el => {
                el.addEventListener('change', () => {
                    loadOptions('dosen');
                    loadOptions('ruangan');
                });
            });

            if (elMatkul.value && elHari.value && elJam.value) {
                loadOptions('dosen');
                loadOptions('ruangan');
            }
        });
    </script>
    @endpush
        </form>
    </div>
</x-app-layout>