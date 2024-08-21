import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/lidofon.css',
                'resources/js/app.js',
                'resources/js/daily-competition.js',
            ],
            refresh: true,
        }),
    ],
});
