<template>
	<div v-if="element_data.id">
		<v-b-modal
			:id               = "element_data.id"
			  class           = "modal fade"
			  tabindex        = "-1"
			  aria-labelledby = "ModalLabel"
			  aria-hidden     = "true"
		>
		</v-b-modal>
	</div>
</template>

<script setup lang="ts">
	import {Element} from "@/utils/Element.ts";

		const {element_data} = defineProps({
			element_data: {type: Element, required: true}
		});
</script>
