<template>
	<div :class = "'block-right' + ' ' + c_arrow_right">

		<template v-if = "c_arrow_right != 'close'">
			<div>
				<NavGuides
					  @arrowAlter = "arrowAlterNav"
					:guides       = "guides_nav_right"
					:active       = "guides_nav_right[0]"
					:header       = "'true'">
				</NavGuides>
			</div>
			<component :is = "componentActive"></component>
		</template>
	</div>
</template>
<script setup lang = "ts">
	import {ref} from "vue";
	import NavGuides from "@/components/ui/NavGuides.vue";
	import Detail from "@/components/ui/Detail.vue";
	import Graphic from "@/components/ui/Graphic.vue";
	import Relationship from "@/components/ui/Relationship.vue";

	const props = defineProps<{
		c_arrow_right: string
	}>()
	
	let componentActive = ref(Detail);
	let nav_list        = ref({
		"detalhes"    : Detail,
		"graficos"    : Graphic,
		"relacionados": Relationship
	})

	let guides_nav_right = ['detalhes','graficos','relacionados']
	

	function arrowAlterNav(guide: string) {
		componentActive.value = nav_list.value[guide as keyof typeof nav_list.value];
	}
</script>
<style lang = "scss" scoped src="@/assets/style/scss/modules/_acompaniement_side_right.scss"></style>
