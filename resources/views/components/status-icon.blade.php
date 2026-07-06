@props(['status'])

@php
    $bg = match($status) {
        'hadir' => 'bg-ganjs-secondary-light',
        'terlambat' => 'bg-ganjs-warning-light',
        'tidak_hadir' => 'bg-ganjs-danger-light',
        default => 'bg-gray-100',
    };
    $icon_color = match($status) {
        'hadir' => 'text-ganjs-secondary',
        'terlambat' => 'text-ganjs-warning',
        'tidak_hadir' => 'text-ganjs-danger',
        default => 'text-gray-400',
    };
@endphp

<div class="w-10 h-10 rounded-xl {{ $bg }} {{ $icon_color }} flex items-center justify-center flex-shrink-0">
    @if($status === 'hadir')
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
    @elseif($status === 'terlambat')
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
    @else
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
    @endif
</div>
