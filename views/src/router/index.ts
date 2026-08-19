import {createRouter, createWebHistory} from "vue-router";
import HomeView from "@/views/HomeView.vue";
import AccompanimentView from "@/views/AccompanimentView.vue";
import NotFoundView from "@/views/NotFoundView.vue";


const routes = [
	{path: "/", component: HomeView},
	{path: "/accompaniment", component: AccompanimentView},
	{path: "/:pathMatch(.*)*", component: NotFoundView},
];
console.log(location.href);
export const router = createRouter({history: createWebHistory(), routes});