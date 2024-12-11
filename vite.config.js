import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        watch: {
            usePolling: true,
            interval: 1000,
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/home.css',
                'resources/css/produto.css',
                'resources/bootstrap/bootstrap.min.css',
                'resources/bootstrap/bundle.min.js',
                'resources/js/app.js',
                'resources/js/script.js',
                'resources/js/sweetalert.js',
                'resources/js/bootstrap.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '~bootstrap': '/node_modules/bootstrap', 
        },
    },
});
