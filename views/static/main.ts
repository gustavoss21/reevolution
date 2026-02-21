import {createApp} from "vue";
import type {App} from "vue";
import HomeComponent from "@/components/homeComponent.vue";
import AccompanimentComponent from "@/components/AccompanimentComponent.vue";

import BootstrapVue3 from "bootstrap-vue-3";
import {BModal} from "bootstrap-vue-3";
// Import Bootstrap and BootstrapVue3 CSS files (order is important)
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";

const app: App = createApp({});
app.use(BootstrapVue3);
app.component("b-modal", BModal);
app.component("accompanimentComponent", AccompanimentComponent);
app.component("homeComponent", HomeComponent);
app.mount("#app");
