<template>
  <div>
   <div class="position-relative">
      <label :for="data.id" class="col-form-label"
        >{{ data.label }}:</label
      >
      <input
        :placeholder="data.placeholder"
        :id="data.id"
        class="form-control"
        @input="(e) => emitFunction(data.action, data, e.target.value)"
        v-model="data.value"
        type="text"
      />
      <a
        ref="btn_create"
        v-if="data.action && data.hasChild()"
        class="icon-add"
        data-bs-toggle="collapse"
        href="#block-add"
        role="button"
        aria-expanded="false"
        aria-controls="block-add"
      >
        <div class="icon-add-item icon-add-y"></div>
        <div class="icon-add-item icon-add-x"></div>
      </a>
    </div>
    <span v-if="data.msg_r" class="text-danger">{{ data.msg }}</span>

    <ul
      v-if="data.child['options_search']"
      class="list-group list-event-request"
    >
      <li
        v-for="theme in data.get_child('options_search')"
        :key="theme.id"
        class="list-group-item"
        aria-current="true"
          @dblclick="emitFunction(theme.action,data,theme)"
      >
        {{ theme.name }}
        <template v-if = "data.tag == 'event_name'" >
          <span  @click = "()=>emitFunction('requestLTopic',theme)" class = "corner-more">⇲</span>
          <ul   v-if         = "theme.child['topics']">
            <li   v-for        = "topics in theme.get_child('topics')"
                :key          = "topics.id"
                class        = "list-group-item"
                aria-current = "true"
                @click    = "emitFunction(topics.action,data,topics)">
              {{ topics.name }}
            </li>
          </ul>
        </template>
      </li>
    </ul>
    <div>
      <div
        id="block-add"
        :class="data.class"
        style="width: 18rem"
        v-if="data.hasChild()"
      >
        <div class="card-body">
          <!-- <h5 class="card-title">{{ data.get_child('main',0).label }}</h5> -->
          <form
            @submit.prevent="e=>emitFunction(data.get_child('main',0).action,data,e)"
          >
            <template v-for="input in data.get_child('main',0).get_child()">
              <component v-bind:is="listComponents[input.type]" :data="input"></component>
            </template>
            <div class="mt-3 flex">
              <button
              v-for="button in data.get_child('main',0).get_child('btn_main')"
              :class="button.class"
              :type="button.type"
            >
              {{ button.label }}
            </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <input v-if="data.hasChild('null','id_hidden')"
      hidden
      type="number"
      :name="data.get_child('id_hidden',0).name"
      v-model="data.get_child('',0,true).value"
    />
  </div>
  
</template>
<script>
  import InputRadio from './InputRadio.vue';
  import InputTextarea from './InputTextarea.vue';
  import InputGeneric from './InputGeneric.vue';
  
  export default {
      props: ['data'],
      data() {
          return {
            listComponents:{
              date:InputGeneric,
              text:InputGeneric,
              number:InputGeneric,
              radio:InputRadio,
              textarea:InputTextarea,
              radio:InputRadio
            },
            data_fun:null
          }
      },    
      methods:{
        emitFunction(methodName, domEvent,data) {
          this.$emit("e_function",methodName,domEvent,data)
        }
      },
      components:{InputRadio, InputTextarea, InputGeneric },
  }
</script>
