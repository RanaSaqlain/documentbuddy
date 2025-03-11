import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            wheat: colors.wheat,
            white: colors.white,
            rose: colors.rose,
            pink: colors.pink,
            fuchsia: colors.fuchsia,
            purple: colors.purple,
            violet: colors.violet,
            indigo: colors.indigo,
            blue: colors.blue,
            sky: colors.sky,  
            cyan: colors.cyan,
            teal: colors.teal,
            emerald: colors.emerald,
            green: colors.green,
            lime: colors.lime,
            yellow: colors.yellow,
            amber: colors.amber,
            orange: colors.orange,
            red: colors.red,
            slate: colors.slate,
            zinc: colors.zinc,
            gray: colors.gray,
            stone: colors.stone,
        },
    },

    plugins: [forms],
};
