<template>
  <div>
    <HeaderComponents />

    <main class="mt-4">
      <router-view />
    </main>
  </div>
</template>

<script setup lang = "ts">
import HeaderComponents from "@/components/features/HeaderComponent.vue";
</script>