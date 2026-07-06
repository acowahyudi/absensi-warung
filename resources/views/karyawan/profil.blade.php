<x-karyawan-layout>
    <x-slot:title>Profil</x-slot:title>

    <div class="px-4 pt-6">
        @php $karyawan = auth()->user()->karyawan; @endphp

        {{-- Avatar --}}
        <div class="flex flex-col items-center py-6 mb-6">
            <div class="w-24 h-24 rounded-full bg-ganjs-primary flex items-center justify-center text-white text-4xl font-bold mb-4">
                {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
            </div>
            <h1 class="text-xl font-bold font-display text-ganjs-ink">{{ auth()->user()->nama_lengkap }}</h1>
            <p class="text-ganjs-ink-muted text-sm">{{ auth()->user()->email }}</p>
            @if($karyawan)
                <span class="mt-2 badge-hadir">{{ $karyawan->nik_karyawan }}</span>
            @endif
        </div>

        {{-- Info --}}
        @if($karyawan)
            <div class="card mb-4">
                <h2 class="font-bold text-ganjs-ink mb-3">Informasi Kepegawaian</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-ganjs-ink-muted">Jenis Kelamin</span>
                        <span class="text-sm font-semibold">{{ $karyawan->jenis_kelamin }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-ganjs-ink-muted">Bergabung Sejak</span>
                        <span class="text-sm font-semibold">{{ $karyawan->tanggal_bergabung->locale('id')->isoFormat('D MMMM Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-ganjs-ink-muted">Gaji Pokok</span>
                        <span class="text-sm font-mono font-bold text-ganjs-primary">Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-ganjs-ink-muted">Uang Makan/hari</span>
                        <span class="text-sm font-mono font-bold text-ganjs-secondary">Rp {{ number_format($karyawan->uang_makan_per_hari, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-danger w-full justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
</x-karyawan-layout>
