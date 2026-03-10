<style lang="css" scoped src="@/assets/style/scss/accompaniement.scss"></style>
<template>
	<div>
		<div style="text-align: center; margin-bottom: 50px">
			<h1>Acompanhamento</h1>
		</div>
		<div class="content-blocks">
			<div class="content-data">
				<div :class="'block-left' + ' ' + cb_left">
					<div class="content-icon-arrow-left">
						<IconArrow
							@click="arrowAlter"
							:class="c_arrow"></IconArrow>
					</div>
					<h3 class="title">Filtros</h3>
					<div class="block-search-filter">
						<BFormTags
							v-model="datalistOptiones"
							no-outer-focus>
							<template
								#default="{
									tags,
									inputAttrs,
									inputHandlers,
									tagVariant,
									addTag,
									removeTag,
								}">
								<BInputGroup size="sm">
									<BDropdown
										:text="option_dropdown_filter"
										variant="primary">
										<BDropdownItem @click="option_dropdown_filter = 'Action A'"
											>Action A</BDropdownItem
										>
										<BDropdownItem @click="option_dropdown_filter = 'Action B'"
											>Action B</BDropdownItem
										>
									</BDropdown>
									<BFormInput
										@keyup.enter="
											(value: KeyboardEvent) => setFilterSearch(value)
										"
										list="input-list"
										placeholder="New tag - Press enter to add"
										class="form-control" />
									<BFormDatalist
										id="input-list"
										:options="datalistOptions" />
								</BInputGroup>
								<div>
									<BFormTag
										v-for="tag in datalistOptiones"
										:key="tag"
										:title="tag"
										class="me-1"
										@remove="removeTag(tag)">
										{{ tag }}
									</BFormTag>
								</div>
							</template>
						</BFormTags>
					</div>
					<div class="content-b-form-radio-group">
						<BFormRadioGroup
							class="b-form-radio-group"
							:options="options"
							button-variant="outline-primary"
							size="lg"
							name="radios-btn-outline"
							buttons />
					</div>
					<div class = "content-b-form-radio-group">
						<BFormRadioGroup
							class="b-form-radio-group"
							:options="options_status"
							button-variant="outline-primary"
							size="lg"
							name="radios-btn-outline"
							buttons />
					</div>
					<div class = "content-b-form-radio-group">
						<BFormRadioGroup
							class="b-form-radio-group"
							:options="options_validate"
							button-variant="outline-primary"
							size="lg"
							name="radios-btn-outline"
							buttons />
					</div>
					<div class = "content-b-form-radio-group">
						<BFormRadioGroup
							class="b-form-radio-group"
							:options="options_process"
							button-variant="outline-primary"
							size="lg"
							name="radios-btn-outline"
							buttons />
					</div>
					<div class = "content-b-form-radio-group">
						<BFormRadioGroup
							class="b-form-radio-group"
							:options="options_scope"
							button-variant="outline-primary"
							size="lg"
							name="radios-btn-outline"
							buttons />
					</div>
				</div>

				<div class="block-center">
					<NavGuides
						:guides="guides_nav"
						:active="guides_nav[0]">
					</NavGuides>
					<div class="block-main"></div>
				</div>
				<div :class="'block-right' + ' ' + c_arrow_right">
					<IconArrowContract @click="arrowAlterRight"></IconArrowContract>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
	import NavGuides from "@/components/ui/NavGuides.vue";
	import IconArrow from "@/components/ui/IconArrow.vue";
	import IconArrowContract from "@/components/ui/IconArrowContract.vue";
	import {ref, useTemplateRef} from "vue";
	import {BFormInput} from "bootstrap-vue-next";
	import {Value} from "sass";

	const inputRef               = useTemplateRef("inputRef");
	const c_arrow                = ref("");
	const c_arrow_right          = ref("");
	const cb_left                = ref("");
	let   guides_nav             = ["Tema", "Topico", "Categoria", "Estágio"];
	const search_filter          = ref("");
	const option_dropdown_filter = ref("Thema");
	const datalistOptions        = ["Apple", "Banana", "Grape", "Kiwi", "Orange"];
	let   datalistOptiones       = ref(["Apple", "Banana", "Grape", "Kiwi", "vermelho"]);
	const options                = [
		{text: "recentes", value: "radio1"},
		{text: "+ antigos", value: "radio2"},
	];
	const options_status               = [
		{text: "ativo", value: "radio1"},
		{text: "inativo", value: "radio2"},
	];
const options_validate               = [
		{text: "vigente", value: "radio1"},
		{text: "vencido", value: "radio2"},
	];
	const options_process               = [
		{text: "iniciado", value: "radio1"},
		{text: "porvir", value: "radio2"},
	];
	const options_scope               = [
		{text: "conteudo conciso", value: "radio1"},
		{text: "conteudo extenso", value: "radio2"},
	];
	const arrowAlter = () => {
		c_arrow.value = c_arrow.value === "rotate" ? "" : "rotate";
		cb_left.value = cb_left.value === "close" ? "" : "close";
		console.log(c_arrow.value);
	};
	const arrowAlterRight = () => {
		c_arrow_right.value = c_arrow_right.value === "close" ? "" : "close";
	};

	const datalistSizes = ["Small", "Medium", "Large", "Extra Large"];

	function setFilterSearch(event: KeyboardEvent) {
		if (event.key === "Enter") {
			let inputElement = event.target as HTMLInputElement;
			datalistOptiones.value.push(
				option_dropdown_filter.value + ":" + inputElement.value,
			);
			inputElement.value = "";
		}
	}
</script>
