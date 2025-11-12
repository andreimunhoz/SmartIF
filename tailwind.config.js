import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', 

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php', 
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], 
            },
            colors: {
              'admin-bg': '#111827',
              'admin-card': '#1F2937',
              'admin-border': '#374151',
              'admin-text-primary': '#F3F4F6',
              'admin-text-secondary': '#9CA3AF',
              'admin-accent': {
                '50': '#e6f3ec',
                '100': '#cce8d9',
                '200': '#99d1b3',
                '300': '#66b98e',
                '400': '#33a268',
                '500': '#008B4A',
                '600': '#007A42',
                '700': '#006B39',
                '800': '#005C31',
                '900': '#004D29',
                '950': '#002E19',
              },
            }
        },
    },

    plugins: [forms],
};