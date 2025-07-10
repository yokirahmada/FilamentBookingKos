// tailwind.config.js

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Penting untuk dark mode
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', // Ini akan memindai semua file Blade Anda
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Tambahkan warna custom Anda di sini
                // Contoh dari UI Anda:
                'ngekos-grey': '#A0A0A0', // Ini harus disesuaikan dengan warna abu-abu Anda
                'ngekos-orange': '#FF5500', // Ini harus disesuaikan dengan warna oranye Anda
                'ngekos-green': '#D2EDE4', // Ini harus disesuaikan dengan warna hijau muda Anda
                'ngekos-black': '#1E293B', // Ini harus disesuaikan dengan warna hitam/dark Anda
            },
        },
    },

    plugins: [forms],
};