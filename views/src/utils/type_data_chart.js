export class TypeDataChart {


    static doughnut (data) {
        return {
          labels: data.map((x) => x.label),
          datasets: [
            {
              label: "STATUS DE EVENTOS:",
              data: data.map((x) => x.amount_event),
              backgroundColor: data.map((x) => x.color),
              hoverOffset: 4,
            },
          ],
        };
    }
}