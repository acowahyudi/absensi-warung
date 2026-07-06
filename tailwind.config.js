import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', 'serif'],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                // Design tokens GANJ'S
                ganjs: {
                    bg: '#FBF7F2',
                    surface: '#FFFFFF',
                    ink: '#231F1A',
                    'ink-muted': '#6B6260',
                    primary: '#C1440E',       // terracotta — CTA utama
                    'primary-dark': '#9E3109',
                    'primary-light': '#F4D4C7',
                    secondary: '#2F5233',     // hijau daun — hadir/sukses
                    'secondary-light': '#D1E8D4',
                    warning: '#D98E04',       // amber — terlambat
                    'warning-light': '#FDF3D0',
                    danger: '#B3261E',        // merah — error/tidak hadir
                    'danger-light': '#FDECEA',
                    border: '#E8DDD6',
                },
            },
            borderRadius: {
                'card': '16px',
                'btn': '999px',
            },
            boxShadow: {
                'card': '0 2px 16px 0 rgba(35, 31, 26, 0.08)',
                'btn': '0 4px 20px 0 rgba(193, 68, 14, 0.35)',
                'btn-pulse': '0 0 0 20px rgba(193, 68, 14, 0)',
            },
            animation: {
                'pulse-ring': 'pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-up': 'slide-up 0.3s ease-out',
                'fade-in': 'fade-in 0.25s ease-out',
                'spin-slow': 'spin 2s linear infinite',
            },
            keyframes: {
                'pulse-ring': {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(193, 68, 14, 0.4)' },
                    '50%': { boxShadow: '0 0 0 24px rgba(193, 68, 14, 0)' },
                },
                'slide-up': {
                    '0%': { transform: 'translateY(16px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
