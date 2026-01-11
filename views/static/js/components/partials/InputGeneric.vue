<template>
  <div>
    <label :for="data.id" class="col-form-label">{{ data.label }}:</label>
    <input
      :placeholder="data.placeholder"
      :id="data.id"
      :type="data.type"
      class="form-control"
      :name="data.name"
      v-model="data.value"
    />
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
