import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/index.ts',
                'resources/js/highlight.ts',
                'resources/js/emoji.ts',
                'resources/js/cp/index.ts',
                'resources/css/global/app.css',
                'resources/css/cp/app.css',
            ],
            publicDirectory: 'resources/dist',
        }),
        // legacy({
        //     targets: ['defaults', 'not IE 11']
        // }),
    ],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]'
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
