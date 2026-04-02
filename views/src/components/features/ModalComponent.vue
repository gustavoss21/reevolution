<template>
	
	<BModal
      v-model = "show"
      size    = "lg"
      title   = "First Modal"
      ok-only
      no-stacking
			id = "modalAcompaniment"
    >
      <div>
			<div>
				<NavGuides
					  @arrowAlter = "arrowAlterNav"
					:guides       = "guides_nav_right"
					:active       = "guides_nav_right[0]"
				</NavGuides>
			</div>
			<component :is = "componentActive"></component>
	  </div>
    </BModal>
	
</template>

<script setup lang = "ts">
	import AcompaniementRightComponent from "@/components/features/AcompaniementRightComponent.vue";

	import {Element} from "@/utils/Element.ts";
	import {BModal} from 'bootstrap-vue-next/components/BModal'
	import {BButton} from 'bootstrap-vue-next/components/BButton'
	import NavGuides from "@/components/ui/NavGuides.vue";
	import Detail from "@/components/ui/Detail.vue";
	import Graphic from "@/components/ui/Graphic.vue";
	import Relationship from "@/components/ui/Relationship.vue";
	import { computed, ref } from 'vue'

	let componentActive  = ref(Detail);
	let guides_nav_right = ['detalhes','graficos','relacionados']
	let nav_list         = ref({
		"detalhes"    : Detail,
		"graficos"    : Graphic,
		"relacionados": Relationship
	})
	const props = defineProps({
		id: {type: Boolean, required: true}
	});

	const show = computed(() => {
		return props.id;
	})

	function arrowAlterNav(guide: string) {
		componentActive.value = nav_list.value[guide as keyof typeof nav_list.value];
	}
</script>
