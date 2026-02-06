<template>
  <div class="example-component">
    <h1>{{ message }}</h1>
    <p>Contador: {{ count }}</p>
    <button @click="increment" class="btn btn-primary">
      Incrementar
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const count = ref<number>(0)
const message = ref<string>('Bem-vindo ao Vue + TypeScript')

const increment = (): void => {
  count.value++
}

const doubleCount = computed<number>(() => {
  return count.value * 2
})
</script>

<style scoped>
.example-component {
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin: 20px 0;
}

.example-component h1 {
  color: #35495e;
  font-size: 24px;
  margin-bottom: 10px;
}

.example-component p {
  color: #666;
  font-size: 18px;
  margin: 10px 0;
}

.example-component button {
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}
</style>
