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
                    bg: '#FEF8EC',
                    surface: '#FFFFFF',
                    ink: '#2C1A14',
                    'ink-muted': '#735F58',
                    primary: '#DE1B0A',       // merah brand utama — CTA utama
                    'primary-dark': '#B71408',
                    'primary-light': '#FCE8E6',
                    secondary: '#185E15',     // hijau daun brand — hadir/sukses
                    'secondary-light': '#E2F1E1',
                    warning: '#E09700',       // amber/gold — terlambat
                    'warning-light': '#FEF6D0',
                    danger: '#BA1A1A',        // merah gelap — error/tidak hadir
                    'danger-light': '#FDECEB',
                    border: '#EADCC9',
                },
            },
            borderRadius: {
                'card': '16px',
                'btn': '999px',
            },
            boxShadow: {
                'card': '0 2px 16px 0 rgba(44, 26, 20, 0.06)',
                'btn': '0 4px 20px 0 rgba(222, 27, 10, 0.35)',
                'btn-pulse': '0 0 0 20px rgba(222, 27, 10, 0)',
            },
            animation: {
                'pulse-ring': 'pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-up': 'slide-up 0.3s ease-out',
                'fade-in': 'fade-in 0.25s ease-out',
                'spin-slow': 'spin 2s linear infinite',
            },
            keyframes: {
                'pulse-ring': {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(222, 27, 10, 0.4)' },
                    '50%': { boxShadow: '0 0 0 24px rgba(222, 27, 10, 0)' },
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
