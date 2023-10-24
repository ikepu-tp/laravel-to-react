import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/react/index.tsx'
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
                'resources/react/components/**',
            ],
        }),
        react(),
    ],
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
    resolve: {
        alias: {
            '~': '/resources/react',
        },
    },
    build: {
        manifest: true,
    }
});
