import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue(), vueDevTools()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./views/static", import.meta.url)),
      vue: "vue/dist/vue.esm-bundler.js",
    },
  },
  build: {
    outDir: "../public/static",
    emptyOutDir: true,
  },
  server: {
    origin: "http://localhost:5173",
    strictPort: true,
  },
})
