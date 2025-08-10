import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
   server: {
    host: '0.0.0.0',
    cors: true,
    port: 5173,
    strictPort: true,
    origin: 'http://192.168.68.106:5173',  // Add this line with your IP and vite port
    },

});
