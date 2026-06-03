import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const suppressStorageUrlWarnings = () => ({
    name: 'suppress-storage-url-warnings',
    enforce: 'post',
    configResolved(config) {
        const original = config.logger.warn.bind(config.logger);
        config.logger.warn = (msg, ...args) => {
            if (typeof msg === 'string' && msg.includes('/storage/')) return;
            original(msg, ...args);
        };
    },
});

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        suppressStorageUrlWarnings(),
    ],
    /*    server: {
            watch: {
                ignored: ['**!/storage/framework/views/!**'],
            },
        },*/
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ["legacy-js-api"],
            },
        },
    },
    build: {
        rolldownOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/swiper')) return 'swiper';
                    if (id.includes('node_modules/@fancyapps')) return 'fancybox';
                    if (id.includes('node_modules/imask')) return 'imask';
                },
            },
        },
    },
});
