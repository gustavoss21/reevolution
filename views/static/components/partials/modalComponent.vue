<template>
  <div v-if="element_data.id">
    <div
      class="modal fade"
      tabindex="-1"
      aria-labelledby="ModalLabel"
      aria-hidden="true"
      :id="element_data.id"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <slot></slot>
          <div class="modal-body">
            <form method="POST"
              ref="form-modal"
              :id="element_data.name"
            >
              <div v-for="value in element_data.child['main']">
                <div class="mb-3">
                  <component v-bind:is="listComponents[value.type]" @e_function="emitFunction" :data="value"></component>
                </div>
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
            <button
              v-for="btn in element_data.child['btn_main']"
              :type="btn.type"
              :class="btn.class"
              :data-bs-dismiss="btn.dismiss === false ? null : 'modal'"
              @click.prevent="emitFunction(element_data.action, element_data, $refs['form-modal'])"
            >
              {{ btn.label }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import '@/css/modal.css';
import InputSearch from "./InputSearch.vue";
import InputRadio from './InputRadio.vue';
import InputTextarea from './InputTextarea.vue';
import InputGeneric from './InputGeneric.vue';

export default {
  props:['element_data'],
  data(){
    return{
      listComponents:{
        date:InputGeneric,
        text:InputGeneric,
        number:InputGeneric,
        radio:InputRadio,
        request:InputSearch,
        textarea:InputTextarea,
        radio:InputRadio
      },
      
    }
  },
computed:{
  fullMessage(){
    return this.fullMessage
  }
},
  methods: {
    /**
     * Handle dynamic button clicks.
     * - if btn.click is a string and matches a local method, call it
     * - else if btn.click is a string, emit event with that name to parent
     * - else if btn.click is a function, call it with current context
     */
    emitFunction(methodName, domEvent,data) {
      this.$emit("e_function",...[methodName,domEvent,data]);
    },
  },
  
  // components: INPUT,
components:{InputSearch, InputRadio, InputTextarea, InputGeneric }
  
};
</script>
