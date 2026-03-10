<style lang="css" scoped src="@/assets/style/scss/modules/_nav.scss"></style>
<template>
	<div class="filter-nav">
		<template v-for = "guide in guides as AccompanimentEnum[]">
			<div v-if="guide == active" class="active">
				{{ guide }}
			</div>
			<div v-else class="deactive" @click="active = guide">
				{{ guide }}
			</div>
		</template>
	</div>
</template>
<script setup lang = "ts">
	import {ref } from "vue";
	import {AccompanimentEnum} from "@/types/interface/AccompanimentInterface.ts";

	const props = defineProps({
		guides: {type: Array, required: true},
		active: String,
	});

	const active = ref(props.active ?? props.guides[0]);
		
		
</script>
