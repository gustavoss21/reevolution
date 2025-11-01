
<template>
    <div class="chart-container">
        <canvas id="TESTE"></canvas>
    </div>
</template>
<script>
    import "https://cdn.jsdelivr.net/npm/chart.js";
    import {TypeDataChart} from "@/js/utils/type_data_chart.js";

    // Chart.register(...registerables);

    export default {
        props: ['id','type','chartData','colors'],

        mounted() {
            this.setColors();
            this.renderChart();
        },

        methods: {
            renderChart() {
                const canvas = document.getElementById('TESTE');
                const ctx = canvas.getContext('2d');

                // build config from TypeDataChart or fallback to simple dataset
                const data = TypeDataChart.doughnut(this.chartData)

                // helper: truncate
                const truncate = (text, max = 24) => {
                    if (!text) return '';
                    return text.length > max ? text.slice(0, max - 1) + '…' : text;
                };

                const config = {
                    type: this.type,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 18,
                                    padding: 8,
                                    font: { size: 12 },
                                    color: '#333',
                                    // generate multi-line labels so the icon appears above the text
                                    generateLabels(chart) {
                                        return chart.data.labels.map((lbl, i) => ({
                                            // first line empty (icon will be drawn next to it), second line the truncated label
                                            text: ['', truncate(lbl, 24)],
                                            fillStyle: chart.data.datasets[0].backgroundColor?.[i] || '#ccc',
                                            hidden: chart.data.datasets[0].data?.[i] === 0,
                                            index: i
                                        }));
                                    }
                                }
                            }
                        }
                    }
                };

                // destroy previous instance if exists
                if (this._chartInstance) {
                    try { this._chartInstance.destroy(); } catch(e){}
                }
                this._chartInstance = new Chart(ctx, config);
            },

            setColors() {
                // Logic to set colors based on this.colors prop
                this.chartData.forEach(data => {
                        data.color = this.colors[data.status];
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
