import {fileURLToPath, URL} from "node:url";

import {defineConfig} from "vite";
import vue from "@vitejs/plugin-vue";
import vueDevTools from "vite-plugin-vue-devtools";

// https://vite.dev/config/
export default defineConfig({
	plugins: [
		vue({
			template: {
				compilerOptions: {
					// ...
				},
				transformAssetUrls: {
					video: ["src", "poster"],
					source: ["src"],
					img: ["src"],
					image: ["xlink:href", "href"],
					use: ["xlink:href", "href"],
				},
			},
		}),
		vueDevTools(),
	],
	resolve: {
		alias: {
			"@": fileURLToPath(new URL("./views/src", import.meta.url)),
			"vue": "vue/dist/vue.esm-bundler.js",
		},
	},
	build: {
		outDir: "../public/static", // saída do build para o PHP
		emptyOutDir: true,
	},
	server: {origin: "http://localhost:5173", strictPort: true},
});
