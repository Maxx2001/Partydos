import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/css/mail.css',
            buildDirectory: './mail',
            refresh: true,
        }),

    ],

    server: {
        strictPort: true,
        port: 8383,
    }
});
