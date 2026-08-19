import {type Component, createApp} from "vue";
import App from "./App.vue";
import {router} from "@/router";
import {createBootstrap, Components, Directives} from "bootstrap-vue-next";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-next/dist/bootstrap-vue-next.css";

  // import {Component} from 'bootstrap-vue-next';
  // const APP = createApp(App).use(router).mount("#app");
console.log(router);
const app = createApp(App).use(router);
app.use(createBootstrap());

for (const name in Components) {
	app.component(name, Components[name as keyof typeof Components] as Component);
}
for (const name in Directives) {
	app.directive(
		name.replace(/^v/, ""),
		Directives[name as keyof typeof Directives],
	);
}

app.mount("#app");
