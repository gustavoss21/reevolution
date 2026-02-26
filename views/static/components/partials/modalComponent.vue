<template>
	<div v-if="element_data.id">
		<v-b-modal
			:id               = "element_data.id"
			class           = "modal fade"
			tabindex       = "-1"
			aria-labelledby = "ModalLabel"
			aria-hidden     = "true"
		>
			<div class           = "modal-dialog">
			<div class           = "modal-content">
					<slot></slot>
					<div class="modal-body">
						<form
							method="POST"
							ref="form-modal"
							:id="element_data.name">
							<div v-for="value in element_data.child['main']">
								<div class="mb-3">
									<component
										v-bind:is="listComponents[value.type as InputTypeComponent]"
										@e_function="emitFunction"
										:data="value">
									</component>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button
							v-for="btn in element_data.child['btn_main']"
							:class="btn.class"
							:data-bs-dismiss="btn.dismiss === false ? null : 'modal'"
							@click.prevent="
								emitFunction(
									element_data.action,
									element_data,
									$refs['form-modal'],
								)
							">
							{{ btn.label }}
						</button>
					</div>
				</div>
			</div>
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
