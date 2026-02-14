import { createApp } from "vue";
import type { App } from "vue";
import HomeComponent from "@/components/homeComponent.vue";


const app: App = createApp(HomeComponent);
app.mount("#app");
