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

<script lang="ts">
	import InputSearch from "./InputSearch.vue";
	import InputRadio from "./InputRadio.vue";
	import InputTextarea from "./InputTextarea.vue";
	import InputGeneric from "./InputGeneric.vue";
	import {InputTypeComponent} from "@/interface/ElementInterface.ts"
	import {Element} from "@/utils/Element.ts";

	export default {
		props: {element_data: {type: Element, required: true}},

		data() {
			return {
				listComponents:{
					date: InputGeneric,
					text: InputGeneric,
					number: InputGeneric,
					radio: InputRadio,
					request: InputSearch,
					textarea: InputTextarea,
				},
			};
		},
		methods: {
			/**
			 * Handle dynamic button clicks.
			 * - if btn.click is a string and matches a local method, call it
			 * - else if btn.click is a string, emit event with that name to parent
			 * - else if btn.click is a function, call it with current context
			 */
			emitFunction(methodName: string, domEvent: Element, data: any) {
				this.$emit("e_function", ...[methodName, domEvent, data]);
			},
		},

		// components: INPUT,
		components: {InputSearch, InputRadio, InputTextarea, InputGeneric},
	};
</script>
