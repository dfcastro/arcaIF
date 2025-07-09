// ARQUIVO: tailwind.config.js

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors'; // <<-- 1. IMPORTE AS CORES

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        'bg-pink-500',
        'bg-blue-500',
        'bg-if-green-500',
        'bg-red-500',
        'bg-yellow-500',
        'bg-gray-500',],


    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'if-green': {
                    '50': '#f0fdf4',
                    '100': '#dcfce7',
                    '200': '#bbf7d0',
                    '300': '#86efac',
                    '400': '#4ade80',
                    '500': '#22c55e',
                    '600': '#16a34a',
                    '700': '#15803d',
                    '800': '#166534',
                    '900': '#14532d',
                    '950': '#052e16',
                },
                // >> 2. ADICIONE A PALETA DE CORES 'PINK' AQUI <<
                pink: colors.pink,
            },
        },
    },

    plugins: [forms],
};