<style lang="css" scoped src="@/assets/style/scss/modules/_nav.scss"></style>
<template>
	<div class="content-nav">
		<h3 v-if="header" class="title">{{header}}</h3>
		<div class="filter-nav">
			<template v-for = "guide in guides as AccompanimentEnum[]">
				<div v-if="guide == active" class="active">
					{{ guide }}
				</div>
				<div v-else class="deactive" @click="alterNav(guide)">
					{{ guide }}
				</div>
			</template>
		</div>
	</div>
</template>
<script setup lang = "ts">
	import {ref } from "vue";
	import {AccompanimentEnum} from "@/types/interface/AccompanimentInterface.ts";

	const emit = defineEmits(
		{
			arrowAlter(payload: string){
				return payload;
			}
		}
	)

	const props = defineProps({
		guides: {type: Array, required: true},
		active: String,
		header: String
	});

	const active = ref(props.active ?? props.guides[0]);

	function alterNav(guide: string) {
		active.value = guide;
		emit("arrowAlter", guide);

	}
		
		
</script>
