import { isArray }     from "chart.js/helpers";
import { ManageChart } from "./ManageChart";

import type {ChartConfiguration} from "chart.js";
type IconClass = {date: string,priority: string,started_study: string,more_advance: string,Lot_to_discurss: string,can_explain: string};
type ChartData = {labels: string[], datasets: {label: string, data: number[], backgroundColor: string[], hoverOffset: number}[]};
type Theme = {chart:Record<string,any> ,score: number, id:number, icon_class: IconClass,name: string,topics: Topic[], days: number};
type Topic = {chart:Record<string,any>,score: number, id:number, icon_class: IconClass,name: string,stages: Stage[],  days: number};
type Stage = {chart:Record<string,any>,score: number, id:number, icon_class: IconClass,name: string,partial_score: number,priority: number,more_advance: number,Lot_to_discurss: number};
export class ManageData
{
    public  orderActived: string;
    private orderList   : string[]            = ['themes', 'topics', 'categories', 'stages'];
    public  listTitles                        = ["Tema", "Tópico", "Categoria", "Estágio"]
    public  dataList: Theme[]|Topic[]|Stage[] = []
    private orderListTranslate: Record<string, string> = {
        'Tema' : 'themes'    ,
        'Tópico' : 'topics'    ,
        'Categoria' : 'categories',
        'Estágio' : 'stages'    
    };
    private orderListFunction: Record<string, Function> = {
        themes    : this.orderByTheme,
        topics    : this.orderByTopic,
        categories: this.orderByCategory,
        stages    : this.orderByStage
    };
    public  orderCurrent : string;
    public  data         : Record<string, any>
    private dataRecovered: Record<string, any>;
    private dataChart: Record<number, any> = {};
    
    constructor() {
        this.orderCurrent = 'themes';
        this.orderActived = 'Tema';
        
    }

    setChartData(dataInstance: Theme|Topic|Stage, totalized: number){
        
        const dataChart           = this.dataChart[dataInstance['id']]??{data: [], label: '', colors: [], labels: []};
        dataChart['score']  = Object.hasOwn(dataInstance, 'score') ? dataInstance['score'] : 0;

        let color = '';
        let label = '';
        let valueswatch = []
        if(dataInstance['score'] > 4){
            color = "rgb(255, 99, 132)";
            label = "Ruim";
            valueswatch = [5,10]

        }else if(dataInstance['score'] > 3){
            color = "rgb(255, 205, 86)";
            label = "Atenção";
            valueswatch = [3,4]

        }else{
            color = "rgb(54, 162, 235)";
            label = "Bom";
            valueswatch = [0,2]
        }
        if(dataChart['labels'].includes(label)){
            let index    = dataChart['data'].findIndex((x: number) => x >= valueswatch[0] && x <= valueswatch[1]);
            dataChart['data'][index] ++ ;
            return;
        }

        dataChart['colors'].push(color)
         dataChart['labels'].push(label)
        
        dataChart['data'] = [...dataChart['data'],1];
        dataChart['label'] = 'Aproveitamento';
        this.dataChart[dataInstance['id']] = dataChart;
    }

    getData(){
        return this.data
        
    }

    setData(data: Record<string, any>){
        this.dataRecovered = data;
        this.orderBy(this.orderActived);
    }

    extractDataList(data:any[]){
        let newDataextracted: {name:string,id:string|number,icon_class:IconClass}[] = [];
            newDataextracted                                   = data;
            
        if(isArray(data[0])){
            newDataextracted = [];

            data.forEach(item => {
                newDataextracted.push(...item);
                console.log('extractDataList item', item)
            });
        }

        return newDataextracted;

    }

    orderBy(order: string){
        
        let orderTranslate    = this.orderListTranslate[order];
            this.orderActived = order;
        
        if(this.orderList.includes(orderTranslate)){
            this.orderCurrent = orderTranslate;
            this.orderListFunction[orderTranslate].call(this);

        }
     '  '}

    getOrder(){
        return this.orderActived;
    }

    orderByTheme(){

        this.analysisDataTheme()
        this.dataList = this.dataRecovered['themes'] ?? [];
        this.data = this.dataRecovered;

    }

    configChart(id: number){

        let manageChart = new ManageChart();
        let dataChart   = this.dataChart[id]??{data: [], label: '', colors: [], labels: []};
        manageChart.setLabelsChart(dataChart['labels']);
        manageChart.setBackgroundColorChart(dataChart['colors']);

        return manageChart.getChartData(dataChart['data'], dataChart['label']);
    }
    
    orderByTopic(){
        let dataForFormat: Theme[] = this.dataRecovered['themes'];
        let newData: Record<string,                         any> = {topics:[]};
        
        dataForFormat.forEach(element => {
            let dataOrder = element['topics'];
              // dataOrder['themes'] = element['name'];
            newData['topics'].push(...this.extractDataList(dataOrder));

        });

        this.dataList = newData['topics'];
        this.data     = newData;
        this.analysisDataTopic()

    }
    
    orderByCategory(){
        type TypeData                = {category: Record<string, any>,name: string}[];
        let  dataForFormat: TypeData = this.data['themes'];
        let  newData: Record<string,  any> = {topics:[]};
        
        dataForFormat.forEach(element => {
            let dataOrder = element['category'];
            newData['category'].push(element['category']);

        });

         this.dataList = newData['stages'];
         this.data     = newData;
    }
    
    orderByStage(){
        type TypeThemeData                           = {topics: Topic[],name: string}[];
        let  dataForFormat: TypeThemeData            = this.dataRecovered['themes'];
        let  newData: Record<string,                  any> = {stages:[]};
        let  dataOrderStageWhitoutDuplicate: Stage[] = [];
        let  indexUnique: number[]                   = [];
                
        dataForFormat.forEach(theme => {
            theme['topics'].forEach(topic => {
                let dataOrderStage        = topic['stages'];

                dataOrderStage.forEach((stage: Stage) => {
                    if(!indexUnique.includes(stage['id'])){ 
                        dataOrderStageWhitoutDuplicate.push(stage);
                        indexUnique.push(stage['id']);
                    };
                });
            });

        });
         this.dataList = dataOrderStageWhitoutDuplicate
         this.data['stages'] = dataOrderStageWhitoutDuplicate;
        this.analysisDataStage()
    }

    analysisDataTheme(){
        if(!this.dataRecovered['themes'])return;

        let counter = 0;

        this.dataRecovered['themes'].forEach((theme: Theme) => {
            if(!theme['icon_class']) {
                theme['icon_class'] = {date: '',priority: '',started_study: '',more_advance: '',Lot_to_discurss: '',can_explain: ''};
            }

            if(!theme['topics']) return;

            theme['topics'].forEach((topic: Topic) => {
                this.analysisDate(topic,theme);
                this.analysisCanExplain(topic,theme);
                this.analysisStartedStudy(topic,theme);

                if(!topic['stages']) return;
                
                topic['stages'].forEach((stage: Stage) => {
                   counter++
                   this.analysisPriority(stage, theme,counter)
                   this.analysisScore(stage, theme,counter)
                   this.analysisMoreAdvance(stage, theme)
                   this.analysisLotTodiscurss(stage, theme)
                   this.setChartData(theme, counter++)
                });
            });

            theme['chart'] = this.configChart(theme['id']) as ChartConfiguration;
        }
    )
    }

    analysisDataTopic(){
        if(!this.data['topics'])return;

        let counter       = 0;
            this.dataList = this.dataList as Topic[];

        this.dataList.forEach((topic: Topic) => {
            if(!topic['icon_class']) {
                topic['icon_class'] = {date: '',priority: '',started_study: '',more_advance: '',Lot_to_discurss: '',can_explain: ''};
            }

            this.analysisDate(topic,topic );
            this.analysisCanExplain(topic,topic );
            this.analysisStartedStudy(topic,topic );
            this.setChartData(topic, counter++)

            topic['stages'].forEach((stage: Stage) => {
                counter++
                this.analysisPriority(stage,topic ,counter)
                this.analysisScore(stage,topic ,counter)
                this.analysisMoreAdvance(stage,topic )
                this.analysisLotTodiscurss(stage,topic )
            });

            topic['chart'] = this.configChart(topic['id']) as ChartConfiguration;
          

        }
    )

    }

     analysisDataStage(){
        if(!this.data['stages'])return;

        let counter       = 0;
            this.dataList = this.dataList as Stage[];
        
        this.dataList.forEach((stage: Stage) => {
            if(!stage['icon_class']) {
                stage['icon_class'] = {date: '',priority: '',started_study: '',more_advance: '',Lot_to_discurss: '',can_explain: ''};
            }

            counter++
            this.analysisPriority(stage, stage ,counter)
            this.analysisScore(stage, stage ,counter)
            this.analysisMoreAdvance(stage, stage )
            this.analysisLotTodiscurss(stage, stage )
            this.setChartData(stage, counter++)
            stage['chart'] = this.configChart(stage['id']) as ChartConfiguration;
        }


    )
    }


    analysisScore(stage: Stage,theme: Theme|Topic|Stage, totalized:number){
      
        if(stage===theme){
            theme['score'] = parseFloat(stage['partial_score'].toFixed(2));
            return;
        }
        let score = 0;
        
        score += stage['partial_score'];
        totalized++;

        theme['score'] = parseFloat((score/ totalized).toFixed(2));

    }

    analysisDate(topic: Record<string, any>, theme: Theme|Topic){
        theme['days']                = theme['days']??0;
        const dateNow                = (new Date()).getTime();
        const dataTopic              = (new Date(topic['end_date'])).getTime();
        const diffTime               = Math.abs(dateNow - dataTopic);
        const diffDays               = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        theme['days']               += diffDays;
        theme['icon_class']['date']  = diffDays > 10 ?'status-alert':'status-good'
    }

    analysisCanExplain(topic: Record<string, any>, theme: Record<string, any>){
          // Implementation for analyzing if a topic can be explained
        let valueOperation = topic['can_explain'] === 1?1:-1;
        theme['can_explain'] = theme['can_explain']??0;
        theme['can_explain'] += valueOperation;
        theme['icon_class']['can_explain'] =  theme['can_explain'] > 1 ?'status-alert':'status-good'
    }

    analysisPriority(stage: Record<string, any>, theme: Record<string, any>,totalized:number){
        if(stage===theme){
            theme['icon_class']['priority'] = theme['priority'] > 10 ?'status-alert':'status-good'
            return;
        }
        // Implementation for analyzing priority
        let valueOperation = stage['priority'];
        theme['priority'] = theme['priority']??0;
        theme['priority'] = (valueOperation+  theme['priority'])/totalized;
         theme['icon_class']['priority'] = theme['priority'] > 10 ?'status-alert':'status-good'
    }

    analysisStartedStudy(topic: Record<string, any>, theme: Record<string, any>){
        // Implementation for analyzing priority
        let valueOperation = topic['started_study'] === 1?1:-1;
        theme['started_study'] = theme['started_study']??0;
        theme['started_study'] = (valueOperation+  theme['started_study']);
        theme['icon_class']['started_study'] =  theme['started_study'] > 1 ?'status-alert':'status-good'
    }

    analysisMoreAdvance(stage: Record<string, any>, theme: Record<string, any>){
        if(stage===theme){
            theme['icon_class']['more_advance'] = theme['more_advance'] > 1 ?'status-alert':'status-good'
            return;
        }
        // Implementation for analyzing priority
        let valueOperation = stage['more_advance'] === 1?1:-1;
        theme['more_advance'] = theme['more_advance']??0;
        theme['more_advance'] = (valueOperation+  theme['more_advance']);
        theme['icon_class']['more_advance'] =  theme['more_advance'] > 1 ?'status-alert':'status-good'

    }

    analysisLotTodiscurss(stage: Record<string, any>, theme: Record<string, any>){
        if(stage===theme){
            theme['icon_class']['Lot_to_discurss'] = theme['Lot_to_discurss'] > 1 ?'status-alert':'status-good'
            return;
        }
        // Implementation for analyzing priority
        let valueOperation = stage['Lot_to_discurss'] === 1?1:-1;
        theme['Lot_to_discurss'] = theme['Lot_to_discurss']??0;
        theme['Lot_to_discurss'] = (valueOperation+  theme['Lot_to_discurss']);
        theme['icon_class']['Lot_to_discurss'] =  theme['Lot_to_discurss'] > 1 ?'status-alert':'status-good'

    }
}

