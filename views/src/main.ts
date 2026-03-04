import {createApp} from "vue";
import App from "./App.vue";

import {router} from "@/router";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-next/dist/bootstrap-vue-next.css";

// const APP = createApp(App).use(router).mount("#app");
const APP = createApp(App).use(router).mount("#app");
