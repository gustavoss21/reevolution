<template>
  <canvas :width = "width" :id = "props.id"></canvas>
</template>

<script setup lang = "ts">
import { onMounted} from 'vue'
import {Chart,type ChartConfiguration, registerables} from 'chart.js';

Chart.register(...registerables)

    const props = defineProps<{
        id           : string,
        settingsData : ChartConfiguration
        width       ?: string
    }>()

  onMounted(() => {
    createChart(props.settingsData)
  })

  function createChart(chartData: ChartConfiguration) {
    const canvas = document.getElementById(props.id) as HTMLCanvasElement

    new Chart(canvas, chartData)
  }

</script>