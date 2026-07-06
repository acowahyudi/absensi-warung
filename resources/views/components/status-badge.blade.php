@props(['status'])

@php
    $classes = match($status) {
        'hadir' => 'badge-hadir',
        'terlambat' => 'badge-terlambat',
        'tidak_hadir' => 'badge-tidak_hadir',
        default => 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600',
    };
    $dot = match($status) {
        'hadir' => 'bg-ganjs-secondary',
        'terlambat' => 'bg-ganjs-warning',
        'tidak_hadir' => 'bg-ganjs-danger',
        default => 'bg-gray-400',
    };
    $label = match($status) {
        'hadir' => 'Hadir',
        'terlambat' => 'Terlambat',
        'tidak_hadir' => 'Tidak Hadir',
        default => ucfirst($status),
    };
@endphp

<span {{ $attributes->class([$classes]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>
