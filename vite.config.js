import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css'
            ],
            refresh: [
                '**/**/**/**/**/**/**/**/*.php',
                '**/**/**/**/**/**/**/*.php',
                '**/**/**/**/**/**/*.php',
                '**/**/**/**/**/*.php',
                '**/**/**/**/*.php',
                '**/**/**/*.php',
                '**/**/*.php',
                '**/*.php',
                '*.php',
                '**/**/**/**/**/**/**/**/*.js',
                '**/**/**/**/**/**/**/*.js',
                '**/**/**/**/**/**/*.js',
                '**/**/**/**/**/*.js',
                '**/**/**/**/*.js',
                '**/**/**/*.js',
                '**/**/*.js',
                '**/*.js',
                '*.js',
                '**/**/**/**/**/**/**/**/*.css',
                '**/**/**/**/**/**/**/*.css',
                '**/**/**/**/**/**/*.css',
                '**/**/**/**/**/*.css',
                '**/**/**/**/*.css',
                '**/**/**/*.css',
                '**/**/*.css',
                '**/*.css',
                '*.css',
            ],
        }),
    ],
});
