<div class="space-y-6">
    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl bg-ganjs-secondary-light flex items-center justify-center mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ganjs-secondary" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="stat-value text-ganjs-secondary">{{ $stats['hadir'] }}</p>
            <p class="stat-label">Hadir Hari Ini</p>
        </div>

        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl bg-ganjs-warning-light flex items-center justify-center mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ganjs-warning" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="stat-value text-ganjs-warning">{{ $stats['terlambat'] }}</p>
            <p class="stat-label">Terlambat</p>
        </div>

        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl bg-ganjs-danger-light flex items-center justify-center mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ganjs-danger" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <p class="stat-value text-ganjs-danger">{{ $stats['tidak_hadir'] }}</p>
            <p class="stat-label">Tidak Hadir</p>
        </div>

        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl bg-ganjs-primary-light flex items-center justify-center mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ganjs-primary" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
            </div>
            <p class="stat-value text-ganjs-primary">{{ $stats['total_karyawan'] }}</p>
            <p class="stat-label">Total Karyawan</p>
        </div>
    </div>

    {{-- Absensi Terbaru Hari Ini --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-ganjs-ink font-display text-lg">Absensi Hari Ini</h2>
            <span class="text-xs text-ganjs-ink-muted">
                {{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}
            </span>
        </div>

        @if($absensiTerbaru->isEmpty())
            <div class="text-center py-12 text-ganjs-ink-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 mx-auto mb-4 opacity-25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-medium">Belum ada absensi hari ini</p>
                <p class="text-sm mt-1">Absensi karyawan akan tampil di sini secara real-time</p>
            </div>
        @else
            <div class="overflow-x-auto -mx-5 px-5">
                <table class="table-ganjs">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Shift</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th class="text-right">Uang Makan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensiTerbaru as $absensi)
                            <tr class="animate-fade-in">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ substr($absensi->karyawan->pengguna->nama_lengkap, 0, 1) }}
                                        </div>
                                        <span class="font-semibold">{{ $absensi->karyawan->pengguna->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="text-ganjs-ink-muted text-sm">{{ $absensi->jadwal?->shift?->nama_shift ?? '-' }}</td>
                                <td><span class="font-mono font-semibold">{{ $absensi->waktu_masuk?->format('H:i') ?? '-' }}</span></td>
                                <td><span class="font-mono text-ganjs-ink-muted">{{ $absensi->waktu_keluar?->format('H:i') ?? '—' }}</span></td>
                                <td><x-status-badge :status="$absensi->status_kehadiran" /></td>
                                <td class="text-right font-mono text-sm">
                                    Rp {{ number_format($absensi->uang_makan_diterima, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Quick links --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('admin.karyawan.create') }}" class="card-hover flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0 group-hover:bg-ganjs-primary group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-ganjs-ink">Tambah Karyawan</span>
        </a>
        <a href="{{ route('admin.jadwal.index') }}" class="card-hover flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0 group-hover:bg-ganjs-primary group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-ganjs-ink">Atur Jadwal</span>
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="card-hover flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0 group-hover:bg-ganjs-primary group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-ganjs-ink">Laporan Gaji</span>
        </a>
        <a href="{{ route('admin.lokasi.index') }}" class="card-hover flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0 group-hover:bg-ganjs-primary group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-ganjs-ink">Lokasi Kantor</span>
        </a>
    </div>
</div>
