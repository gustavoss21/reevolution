export class TypeDataChart {
    constructor (data, chartType){
        if (typeof this[chartType] === 'function') {
            this.chartData = this[chartType](data);
        } else {
            throw new TypeError(`Tipo de gráfico desconhecido: ${chartType}`);
        }
    }

    doughnut (data) {
        return{
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
        }
    }
}