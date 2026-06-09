// vite.article_converter.config.js
import { defineConfig } from 'vite';
import { nodePolyfills } from 'vite-plugin-node-polyfills';

export default defineConfig({
    plugins: [
        nodePolyfills({
            include: ['assert'], 
        }),
    ],
    publicDir: false,
    build: {
        outDir: 'public/js/rmrh',
        minify: false,
        cssMinify: false,
        emptyOutDir: false, 
        rollupOptions: {
            input: 'resources/js/article_converter.js',
            output: {
                // Strips the default 'assets/' prefix from the file path
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
    },
});
