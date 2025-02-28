import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app/index.jsx',
                'resources/js/auth/index.jsx'
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: __dirname + '/resources/js/app/index.jsx',
                auth: __dirname + '/resources/js/auth/index.jsx'
            },
            output: {
                entryFileNames: ({ name }) => `${name}/index.js`,
                chunkFileNames: '[name]/[name].js',
                assetFileNames: 'assets/[name].[ext]'
            }
        },
        commonjsOptions: {
            include: /node_modules/,
            transformMixedEsModules: true,
        },
        outDir: 'public/js'
    }
});
