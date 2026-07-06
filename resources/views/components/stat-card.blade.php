@props(['value', 'label', 'color' => 'primary', 'icon' => null])

@php
    $valueColor = match($color) {
        'green' => 'text-ganjs-secondary',
        'amber' => 'text-ganjs-warning',
        'red' => 'text-ganjs-danger',
        default => 'text-ganjs-primary',
    };
    $bgColor = match($color) {
        'green' => 'bg-ganjs-secondary-light',
        'amber' => 'bg-ganjs-warning-light',
        'red' => 'bg-ganjs-danger-light',
        default => 'bg-ganjs-primary-light',
    };
@endphp

<div class="stat-card">
    @if($icon)
        <div class="w-10 h-10 rounded-xl {{ $bgColor }} flex items-center justify-center mb-1">
            {!! $icon !!}
        </div>
    @endif
    <p class="stat-value {{ $valueColor }}">{{ $value }}</p>
    <p class="stat-label">{{ $label }}</p>
</div>
