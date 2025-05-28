import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const tailwindColors = [
    "bg-red-300", "bg-red-400", "bg-red-500", "bg-red-600", "bg-red-700",
    "bg-blue-300", "bg-blue-400", "bg-blue-500", "bg-blue-600", "bg-blue-700",
    "bg-green-300", "bg-green-400", "bg-green-500", "bg-green-600", "bg-green-700",
    "bg-yellow-300", "bg-yellow-400", "bg-yellow-500", "bg-yellow-600", "bg-yellow-700",
    "bg-purple-300", "bg-purple-400", "bg-purple-500", "bg-purple-600", "bg-purple-700",
    "bg-pink-300", "bg-pink-400", "bg-pink-500", "bg-pink-600", "bg-pink-700",
    "bg-indigo-300", "bg-indigo-400", "bg-indigo-500", "bg-indigo-600", "bg-indigo-700",
    "bg-teal-300", "bg-teal-400", "bg-teal-500", "bg-teal-600", "bg-teal-700",
    "bg-orange-300", "bg-orange-400", "bg-orange-500", "bg-orange-600", "bg-orange-700",
    "bg-gray-300", "bg-gray-400", "bg-gray-500", "bg-gray-600", "bg-gray-700",
    'bg-blue-700', 'hover:bg-blue-500',
    'bg-green-700', 'hover:bg-green-500',
    'bg-orange-700', 'hover:bg-orange-500',
    'bg-purple-700', 'hover:bg-purple-500',
];

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/v-calendar/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'cancel-red': '#FF4545'
            }
        },
    },
    plugins: [forms, typography],
    safelist: tailwindColors
};
