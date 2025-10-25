import { ModelGrafic } from "./utils/charts.js";
import { ApiClient } from "./utils/request.js";
import { BuildHTML } from "./utils/request.js";

let instaceGrafic = new ModelGrafic();
let request = new ApiClient(location.href);

let timeline = request.get("timeline").then((response) => {});

//get status averange
let status_averange = request.get("status-averange").then((response) => {
  response.averange.map(function (averange) {
    let value = response.options[averange.status];
    averange.label = value;
    return averange;
  });

  instaceGrafic.doughnut(response.averange);
});

//get time without study
let without_study = request.get("time-without-study").then((response) => {});

//get event recommendation
let new_event = request.get("new-event-init").then((response) => {});
