<template>
  <div>
    <span :for="data.id" class="col-form-label">
      {{ data.label }}:
      </span>
    <div
      v-for="input_option in data.child['main']"
      :id="data.id"
      class="form-check"
    >
      <input v-if="data.value === input_option.name"
        :name="data.name"
        :class="input_option.class"
        type="radio"
        :id="input_option.id"
        @input="()=>data.value = input_option.value"
        checked
      />
      <input v-else
        :name="data.name"
        :class="input_option.class"
        type="radio"
        :id="input_option.id"
        @input="()=>data.value = input_option.value"
      />
      <label class="form-check-label" :for="input_option.id">
        {{ input_option.label }}
      </label>
    </div>
    <span v-if="data.msg_r" class="text-danger">{{ data.msg }}</span>

  </div>
  
</template>
<script>
    export default {
        props: ['data'],
        data() {
            return {
               
            }
        },    
    }
</script>
