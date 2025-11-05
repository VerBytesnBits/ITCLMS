/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    safelist: [
        // Explicit gradient classes
        "from-emerald-500",
        "to-blue-500",
        "from-amber-500",
        "to-red-500",

        // OR a pattern to cover all "from-*/to-*" colors
        {
            pattern:
                /(from|to|via)-(red|blue|emerald|amber|green|indigo|purple|pink)-(100|200|300|400|500|600|700|800|900)/,
        },
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
