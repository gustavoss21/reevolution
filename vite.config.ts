import {fileURLToPath, URL} from "node:url";

import {defineConfig} from "vite";
import vue            from "@vitejs/plugin-vue";
import vueDevTools    from "vite-plugin-vue-devtools";
import Icons          from 'unplugin-icons/vite'
import Components     from 'unplugin-vue-components/vite'
import IconsResolve   from 'unplugin-icons/resolver'

  // https://vite.dev/config/
export default defineConfig({
	plugins: [
		vue({
			template: {
				compilerOptions: {
					  // ...
				},
				transformAssetUrls: {
					video : ["src", "poster"],
					source: ["src"],
					img   : ["src"],
					image : ["xlink:href", "href"],
					use   : ["xlink:href", "href"],
				},
			},
		}),
		vueDevTools(),
		Components({
			resolvers: [IconsResolve()],
			dts      : true,
		}),
		Icons({
			compiler   : 'vue3',
			autoInstall: true,
		}),
	],
	css: {
		preprocessorOptions: {
			scss: {
				additionalData: `
				@use "@/assets/style/scss/partial/_variables.scss";
				@use "@/assets/style/scss/partial/_colors.scss";
				@use "@/assets/style/scss/partial/_reset.scss";
				@use "@/assets/style/scss/partial/_global.scss";
				`,
			},
		},
	},
	resolve: {
		alias: {
			"@"  : fileURLToPath(new URL("./views/src", import.meta.url)),
			"vue": "vue/dist/vue.esm-bundler.js",
		},
	},
	build: {
		outDir     : "../public/static",   // saída do build para o PHP
		emptyOutDir: true,
	},
	server: {
		origin: "http://localhost:5173", strictPort: true,
		cors: { 
			origin : 'http://reevolution',
			methods: ['GET','POST','PUT','DELETE'],
			allowedHeaders: ['Content-Type', 'Authorization'],

		}
	},
});
