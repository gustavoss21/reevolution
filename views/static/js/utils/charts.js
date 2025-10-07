import "https://cdn.jsdelivr.net/npm/chart.js";

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
    

    this._config.data = {
      labels: data.map((x) => x.label),
      datasets: [
        {
          label: "quantidade de eventos:",
          data: data.map((x) => x.amount_event),
          backgroundColor: [
            "rgb(255, 99, 132)",
            "rgb(255, 205, 86)",
            "rgb(54, 162, 235)"
          ],
          hoverOffset: 4
        },
      ],
    };
   


    this.chart = new Chart(elementForCharts, this._config);
  }
}
