import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: {
                // Light Theme
                'light-main': 'resources/scss/light/assets/main.scss',
                'light-structure': 'resources/scss/layouts/modern-light-menu/light/structure.scss',
                'light-font-icons': 'resources/scss/light/assets/components/font-icons.scss',
                'light-loader': 'resources/scss/layouts/modern-light-menu/light/loader.scss',
                
                // Dark Theme
                'dark-main': 'resources/scss/dark/assets/main.scss',
                'dark-structure': 'resources/scss/layouts/modern-light-menu/dark/structure.scss',
                'dark-font-icons': 'resources/scss/dark/assets/components/font-icons.scss',
                'dark-loader': 'resources/scss/layouts/modern-light-menu/dark/loader.scss',

                // JS
                // 'app': 'resources/layouts/vertical-light-menu/app.js',
            },
            refresh: true,
        }),
    ],
    build: {
        outDir: 'resources/assets',
        emptyOutDir: false,
        rollupOptions: {
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name][extname]';
                    }
                    return 'images/[name][extname]';
                },
            },
        },
    },
});