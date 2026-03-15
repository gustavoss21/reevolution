<template>
<div :class = "'block-left' + ' ' + cb_left">
<div class  = "content-icon-arrow">
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
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        class="b-form-radio-group"
        :options="options_status"
        button-variant="outline-primary"
        size="lg"
        name="radios-btn-outline"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        class="b-form-radio-group"
        :options="options_validate"
        button-variant="outline-primary"
        size="lg"
        name="radios-btn-outline"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        class="b-form-radio-group"
        :options="options_process"
        button-variant="outline-primary"
        size="lg"
        name="radios-btn-outline"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        class="b-form-radio-group"
        :options="options_scope"
        button-variant="outline-primary"
        size="lg"
        name="radios-btn-outline"
        buttons />
</div>
</div>
</template>
<script setup lang = "ts">
import {ref, useTemplateRef} from "vue";
	import {BFormInput} from "bootstrap-vue-next";

//icons
	import IconArrow from "@/components/ui/IconArrow.vue";

 let   datalistOptiones       = ref(["Apple", "Banana", "Grape", "Kiwi", "vermelho"]);
 const option_dropdown_filter = ref("Thema");
 const c_arrow                = ref("");
 const cb_left                = ref("");
 const options                = [
		{text: "recentes", value: "radio1"},
		{text: "+ antigos", value: "radio2"},
	];
    const options_status = [
		{text: "ativo", value: "radio1"},
		{text: "inativo", value: "radio2"},
	];
	const options_validate = [
		{text: "vigente", value: "radio1"},
		{text: "vencido", value: "radio2"},
	];
	const options_process = [
		{text: "iniciado", value: "radio1"},
		{text: "porvir", value: "radio2"},
	];
	const options_scope = [
		{text: "conteudo conciso", value: "radio1"},
		{text: "conteudo extenso", value: "radio2"},
	];
	const datalistOptions = ["Apple", "Banana", "Grape", "Kiwi", "Orange"];

	const arrowAlter = () => {
		c_arrow.value = c_arrow.value === "rotate" ? "" : "rotate";
		cb_left.value = cb_left.value === "close" ? "" : "close";
		console.log(c_arrow.value);
	};
        
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
<style lang = "scss" scoped src = "@/assets/style/scss/modules/_acompaniement_side_left.scss"></style>
