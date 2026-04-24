import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // Force correct URL into public/hot
        {
            name: 'override-hot-file',
            enforce: 'post',  // run after all other plugins
            configureServer(server) {
                server.httpServer?.once('listening', () => {
                    setTimeout(() => {
                        fs.writeFileSync('public/hot', 'https://new.lo/vite-dev');
                        console.log('✅ hot file overridden: https://new.lo/vite-dev');
                    }, 500); // wait for laravel plugin to finish writing
                });
            },
        },
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'new.lo',
            protocol: 'wss',
            clientPort: 443,  // browser connects via Apache on 443
            port: 5173,       // ← Vite WS server stays on 5173, not 443
        },
    },
});
