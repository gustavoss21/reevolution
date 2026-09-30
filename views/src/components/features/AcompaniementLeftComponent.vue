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
        v-model = "showlistOptiones"
        no-outer-focus>
        <template #default = "{tags, inputAttrs, inputHandlers, tagVariant, addTag, removeTag}">
            <BInputGroup size="sm">
                <BDropdown
                    :text     = "option_dropdown_filter.title"
                      variant = "primary"
                      v-on="inputHandlers">
                    
                    <BDropdownItem v-for="setting in inputSearchSettings.slice(1)" @click="option_dropdown_filter = setting"
                        >{{ setting.title }}</BDropdownItem
                    >
                   
                </BDropdown>
                <BFormInput
                id                     = "tags-basic"
                ref                    = "inputRef"
                list                   = "input-list"
                placeholder            = "New tag - Press enter to add"
                class                  = "form-control"
                @keydown.enter.prevent = "mix.setFilterSearch(addTag, $event, option_dropdown_filter)"
                @keyup.enter.prevent   = "$event.target.value = ''" />
                <BFormDatalist
                    id="input-list"
                    :options="datalistOptions" />
            </BInputGroup>
            <div>
                <BFormTag
                      v-for  = "tag in tags"
                    :key     = "tag"
                    :title   = "tag"
                    :variant = "tagVariant"
                      class  = "me-1"
                      @remove = "mix.dropFilterSearch(removeTag, tag)">
                      
                    {{ tag }}
                </BFormTag>
            </div>
        </template>
    </BFormTags>
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @click  = "(value: InputEvent) => mix.setDataFilterSearch('orderTasksForDate', value)"
          class          = "b-form-radio-group"
        :options         = "options_date"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "options-date"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @click        = "(value: InputEvent) => mix.setDataFilterSearch('statusFilter', value)"
          class          = "b-form-radio-group"
        :options         = "status_options"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "status-options"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @click        = "(value: InputEvent) => mix.setDataFilterSearch('filterForExpiredTime', value)"
          class          = "b-form-radio-group"
        :options         = "validate_options"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "validate-options"
    
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        @click = "(value: InputEvent)=>mix.setDataFilterSearch('statedStudy',value)"    
        class="b-form-radio-group"
        :options="process_options"
        button-variant="outline-primary"
        size="lg"
        name="process-options"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        @click = "(value: InputEvent)=>mix.setDataFilterSearch('amountContentOfStudyFilter',value)"
        class="b-form-radio-group"
        :options="scope_options"
        button-variant="outline-primary"
        size="lg"
        name="scope-options"
        buttons />
</div>
</div>
</template>
<script setup lang = "ts">
import {ref, useTemplateRef} from "vue";
import {BFormInput} from "bootstrap-vue-next";
import mix from "@/utils/mixinLeftComponent.ts";

//icons
import IconArrow from "@/components/ui/IconArrow.vue";

var emit = defineEmits(['dataFilter'])
mix.setEmitFunction(emit);

// v-bind               = "inputAttrs"
let showlistOptiones = ref<string[]>([]);

let inputSearchSettings = [
    {title: "Tema", name: "thema"},
    {title: "Tópico", name: "topic"},
    {title: "tag", name: "tags"}
];

const option_dropdown_filter = ref(inputSearchSettings[0]);
const c_arrow                = ref("");
const cb_left                = ref("");
const options_date                = [
    {text: "recentes", value: "desc", disabled: false},
    {text: "+ antigos", value: "asc", disabled: false},
];
const status_options = [
    {text: "ativo", value: "started"},
    {text: "inativo", value: "future"},
];
const validate_options = [
    {text: "vigente", value: "vigente"},
    {text: "vencido", value: "expired"},
];
const process_options = [
    {text: "iniciado", value: "started"},
    {text: "não iniciado", value: "future"},
];
const scope_options = [
    {text: "conteudo conciso", value: false},
    {text: "conteudo extenso", value: true},
];
const datalistOptions = ["Apple", "Banana", "Grape", "Kiwi", "Orange"];

const arrowAlter = () => {
    c_arrow.value = c_arrow.value === "rotate" ? "" : "rotate";
    cb_left.value = cb_left.value === "close" ? "" : "close";
    console.log(c_arrow.value);
};



</script>
<style lang = "scss" scoped src = "@/assets/style/scss/modules/_acompaniement_side_left.scss"></style>
