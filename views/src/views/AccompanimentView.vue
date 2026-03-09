<style lang = "css" scoped src = "@/assets/style/scss/accompaniement.scss"></style>
<template>
	<div>
		<HeaderComponent></HeaderComponent>
		<div style = "text-align: center; margin-bottom: 50px;">
          <h1>Acompanhamento</h1>
        </div>
		<div class="content-blocks">
			<div>
				<div>
					<NavGuides
						:guides = "guides_nav" :active="guides_nav[0]"
					></NavGuides>
				</div>
			</div>
		</div>
	</div>
</template>

<script lang = "ts">
	  // import HomeSide from "./partials/homeSide.vue";
	  // import AddEventComponent from "./partials/addEventComponent.vue";
	  // import {ApiClient} from "@/utils/request.ts";
	
	import NavGuides from "@/components/partials/NavGuides.vue";
	import {AccompanimentEnum} from "@/types/interface/AccompanimentInterface.ts";

	// import {
	// 	EventType,
	// 	PriorityEvent,
	// 	WithoutStudyEvent,
	// } from "@/interface/EventInterface.ts";
	export default {
		created() {
			console.log("AccompanimentComponent crea");
		},
		data() {
			return {
				guides_nav: ["Tema","Topico","Categoria",'Estágio'],
			};
		},

		// components: {AddEventComponent, HomeSide}, //
	};
</script>
