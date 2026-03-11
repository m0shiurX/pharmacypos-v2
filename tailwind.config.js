/** @type {import('tailwindcss').Config} */
module.exports = {
    prefix: 'tw-',
    content: [
        './resources/views/**/*.blade.php',
        './app/**/*.php',
        './Modules/**/*.blade.php',
    ],
    safelist: [
        // Dynamic theme color classes (session('business.theme_color') resolves at runtime)
        { pattern: /tw-bg-green-(50|100|200|400|700|800|900)/ },
        { pattern: /tw-bg-green-(700|800)/, variants: ['hover'] },
        { pattern: /tw-text-green-(500|700|900)/ },
        { pattern: /tw-border-green-(500)/ },
        { pattern: /tw-from-green-(800)/ },
        { pattern: /tw-to-green-(900)/ },
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
