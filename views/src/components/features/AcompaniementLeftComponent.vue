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
                id = "tags-basic"
                ref="inputRef"
                list                   = "input-list"
                placeholder            = "New tag - Press enter to add"
                class                  = "form-control"
                @keydown.enter.prevent = "setFilterSearch(addTag, $event)"
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
                      @remove = "dropFilterSearch(removeTag, tag)">
                      
                    {{ tag }}
                </BFormTag>
            </div>
        </template>
    </BFormTags>
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @click  = "(value: InputEvent) => setDataFilterSearch('orderTasksForDate', value)"
          class          = "b-form-radio-group"
        :options         = "options_date"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "options-date"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @change        = "(value: InputEvent) => setDataFilterSearch('statusFilter', value)"
          class          = "b-form-radio-group"
        :options         = "status_options"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "status-options"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
          @change        = "(value: InputEvent) => setDataFilterSearch('filterForExpiredTime', value)"
          class          = "b-form-radio-group"
        :options         = "validate_options"
          button-variant = "outline-primary"
          size           = "lg"
          name           = "validate-options"
    
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        @change = "(value: InputEvent)=>setDataFilterSearch('statedStudy',value)"    
        class="b-form-radio-group"
        :options="process_options"
        button-variant="outline-primary"
        size="lg"
        name="process-options"
        buttons />
</div>
<div class="content-b-form-radio-group">
    <BFormRadioGroup
        @change = "(value: InputEvent)=>setDataFilterSearch('amountContentOfStudyFilter',value)"
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
import {ApiClient} from "@/utils/request.js";
//icons
import IconArrow from "@/components/ui/IconArrow.vue";

// v-bind               = "inputAttrs"
let showlistOptiones = ref<string[]>([]);
let keyOptions: string[] = [];
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

const optionsForSearch: object[] = []

function makeRequestWhenChange(){
    console.log(optionsForSearch)
    let   request = new ApiClient(location.origin+'/reevolution')
    const params  = JSON.stringify(optionsForSearch);
      // Transformamos em string e depois codificamos para URL
    const dataString = encodeURIComponent(params);
    const url = `/filters-accompaniment?data=${dataString}`;
// params vira "status=ativo&pagina=1&limite=10"

    request.get(url)
    .then((response) => {
        console.log(response);
    }).catch((error) => {
        console.error(error);
    });
}



function setFilterSearch(addTagFn: (tag: string) => void, event: InputEvent) {
    console.log(event);
    
    let inputElement = event.target as HTMLInputElement;
    let formatedOption = option_dropdown_filter.value.title+ ":" + inputElement.value
    // showlistOptiones.value.push(
    //     formatedOption
    // );
    

    optionsForSearch.push({'filterforTable': formatedOption})
    
    setTimeout(
        () => {
            //    inputElement.value = "";
    addTagFn(formatedOption);

        }
        , 10
    );
    
    return makeRequestWhenChange()
}

function dropFilterSearch(dropTagfn: (tag: string)=>void, tag: string) {
    optionsForSearch.map((option: { filterforTable?: string }, index: number) => {
        if (option.hasOwnProperty('filterforTable') && option['filterforTable'] === tag) {
            optionsForSearch.splice(index, 1); // Break the loop
        }
    });
    dropTagfn(tag);
   
    setTimeout(
        () => {
        let input       = document.getElementById('tags-basic') as HTMLInputElement;
        input.value = ''

        }
        , 10
    );
    makeRequestWhenChange()
}

function setDataFilterSearch(key: string, event: InputEvent) {

    let inputElement = event.target as HTMLLabelElement & HTMLInputElement;
    if(!inputElement.value) return;   
    let inputValue = inputElement.value;

    if(keyOptions.includes(key)){
        let droppedOption: object = dropDataFilterSearch(key)[0];
        let keyDropped = key as keyof typeof droppedOption
        keyOptions.splice(keyOptions.indexOf(key), 1);
        
        if(droppedOption[keyDropped] === inputValue){
            droppedOption        = droppedOption;
            setTimeout(() => {
                inputElement.checked = false;
            }, 10);
            return
            }

    }
    
    optionsForSearch.push({[key]: inputValue})
    console.log(optionsForSearch);

    keyOptions.push(key);
    makeRequestWhenChange()
}

function dropDataFilterSearch(key: string) {
    let index = optionsForSearch.findIndex((option:object) => {
        if (option.hasOwnProperty(key)) {
            return true; // Break the loop
        }
        return false;
    });
    if (index === -1)return;

    keyOptions.splice(keyOptions.indexOf(key), 1);
    return optionsForSearch.splice(index, 1);
     
}


</script>
<style lang = "scss" scoped src = "@/assets/style/scss/modules/_acompaniement_side_left.scss"></style>
