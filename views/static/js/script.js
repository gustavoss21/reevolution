import {ModelGrafic} from './utils/charts.js';
import { ApiClient } from "./utils/request.js";

let instaceGrafic = new ModelGrafic();
let request = new ApiClient(location.href);

let data = request.get("media-de-status")
    .then(response=>{
        response.averange.map((function(averange){
            let value = response.options[averange.status];
            averange.label = value;
            return averange;
        }))
        
        instaceGrafic.doughnut(response.averange);

    });
