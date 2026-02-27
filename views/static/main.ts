import {createApp} from "vue";
import type {App} from "vue";
import HomeComponent from "@/components/homeComponent.vue";
import AccompanimentComponent from "@/components/AccompanimentComponent.vue";
import ModalComponent from "@/components/partials/modalComponent.vue";
import Button from "@/components/partials/Button.vue";
import HomeSide from "@/components/partials/homeSide.vue";
import AddEventComponent from "@/components/partials/addEventComponent.vue";
import NavGuides from "@/components/partials/NavGuides.vue";
import HeaderComponent from "@/components/partials/HeaderComponent.vue";
//bootstrap
import BootstrapVue3 from "bootstrap-vue-3";
import {BModal} from "bootstrap-vue-3";
import {BTab} from "bootstrap-vue-3";
import {BTabs} from "bootstrap-vue-3";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";


const app: App = createApp({});
app.use(BootstrapVue3);
app.component("b-modal", BModal);
app.component("b-tab", BTab);
app.component("b-tabs", BTabs);


app.component("HeaderComponent", HeaderComponent);
app.component("AccompanimentComponent", AccompanimentComponent);
app.component("HomeComponent", HomeComponent);
app.component("AddEventComponent", AddEventComponent);
app.component("Button", Button);
app.component("HomeSide", HomeSide);
app.component("ModalComponent", ModalComponent);
app.component("NavGuides", NavGuides);


app.mount("#app");
