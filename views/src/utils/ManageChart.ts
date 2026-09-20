import {ChartTypeRegistry} from "chart.js";

export class ManageChart {
    public labels: string[]           = [];
    public backgroundColors: string[] = [];
    
    setLabelsChart(chartData: string[]) {
       chartData.forEach((label: string) => {
            if (label.length > 2) {
                this.labels.push(label);
            }
        });
    }
    setBackgroundColorChart(colors: string[]) {
       colors.forEach((color: string) => {
            if (color.length > 3) {
                this.backgroundColors.push(color);
            }
        });
    }

    getChartData(chartData: any[],title: string) {
        let type_doughnut: keyof ChartTypeRegistry = "doughnut";

        let dataChart = {
            type: type_doughnut,
            data: {
                labels  : this.labels,
                datasets: [
                    {
                        label          : title,
                        data           : chartData,
                        backgroundColor: this.backgroundColors,
                        hoverOffset: 4,
                    },
                ],
            },
        };

        return dataChart;
    }
}