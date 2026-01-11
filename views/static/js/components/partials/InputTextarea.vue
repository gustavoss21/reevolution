<template>
  <div>
    <label :for="data.id" class="col-form-label">
      {{data.label}}
    </label>
    <textarea
      :placeholder="data.placeholder"
      :id="data.id"
      class="form-control"
      :name="data.name"
      v-model="data.value"
      
    ></textarea>
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
