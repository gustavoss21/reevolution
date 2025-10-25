
<template>
    <div>
        <canvas :id="this.chartData.id"></canvas>
    </div>
</template>
<script>
    import "https://cdn.jsdelivr.net/npm/chart.js";
    import {TypeDataChart} from "@/js/utils/type_data_chart.js";

    // Chart.register(...registerables);

    export default {
        props: ['chartData'],

        mounted() {
            this.renderChart();
        },

        methods: {
            renderChart() {
                const ctx = document.getElementById(this.chartData.id).getContext('2d');
                const data = new TypeDataChart(this.chartData);
                new Chart(ctx, {
                    type: 'bar',
                    data: data.data,
                    options: data.options
                });
            }
        }
    }
</script>
<!-- 


export class ModelGrafic {
  type = "";
  constructor() {
    this._config = { type: "", data: {} };
  }

  /**
   * @argument data array de 3 valores contendo a parte da pizza
   */
  doughnut(data) {
    let copi_data = data;

    this._config.type = "doughnut";
    let elementForCharts = document.getElementById("averange-level");
    

    this._config.data = 
   


    this.chart = new Chart(elementForCharts, this._config);
  } -->
