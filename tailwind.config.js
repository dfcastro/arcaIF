import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // ADICIONE ESTA SECÇÃO DE CORES
            colors: {
                'if-green': '#588157', // Verde principal do logo
                'if-red': '#E63946',   // Vermelho principal do logo
                'if-dark': '#343A40',  // Cinza escuro para textos
            },
        },
    },

    plugins: [forms],
};