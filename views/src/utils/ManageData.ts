import { isArray } from "chart.js/helpers";

export class ManageData
{
    public  orderActived: string;
    private orderList   : string[] = ['themes', 'topics', 'categories', 'stages'];
    public  listTitles             = ["Tema", "Tópico", "Categoria", "Estágio"]
    public dataList: {name:string,id:string|number}[] = []
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
    public orderCurrent: string;
    public data        : Record<string, any>
    private dataRecovered : Record<string, any> = {};

    constructor(data: Record<string, any>={themes:[]}) {
        this.orderCurrent = 'themes';
        this.orderActived = 'Tema';
        this.setData(data);
        
    }

    getData(){
        return this.data
        
    }

    setData(data: Record<string, any>){
        this.data          = data;
        this.dataRecovered = data;
        this.dataList      = this.extractDataList(data[this.orderCurrent]);
        console.log('setData', this.dataList);
    }

    extractDataList(data:any[]){
        let newDataextracted: {name:string,id:string|number}[] = [];
            newDataextracted                                   = data;
        console.log('extractDataList type', typeof data[0])
        console.log('extractDataList array', data)
        console.log('extractDataList', data[0])
        console.log('isArray', data[0])
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
            let data              = this.orderListFunction[orderTranslate].call(this);
            this.setData(data);
        }
    }

    getOrder(){
        return this.orderActived;
    }

    orderByTheme(){
        this.data = this.dataRecovered;
        return this.data;
    }
    
    orderByTopic(){
        type TypeData =  {topics: Record<string, any>,name: string}[];
        let dataForFormat: TypeData = this.data['themes'];
        let newData: Record<string,                         any> = {topics:[]};
        
        dataForFormat.forEach(element => {
            let dataOrder = element['topics'];
            // dataOrder['themes'] = element['name'];
            newData['topics'].push(element['topics']);

        });
        this.data = newData;

        return newData;
    }
    
    orderByCategory(){
        type TypeData                = {category: Record<string, any>,name: string}[];
        let  dataForFormat: TypeData = this.data['themes'];
        let  newData: Record<string,  any> = {topics:[]};
        
        dataForFormat.forEach(element => {
            let dataOrder = element['category'];
            // dataOrder['themes'] = element['name'];
            newData['category'].push(element['category']);

        });

        this.data = newData;
        return newData;
    }
    
    orderByStage(){
        type TypeDataStage = {stages: Record<string, any>,name: string}[];

        type TypeThemeData =  {topics: TypeDataStage,name: string}[];
        let  dataForFormat: TypeThemeData = this.data['themes'];
        let  newData: Record<string,  any> = {stages:[]};
                
        dataForFormat.forEach(theme => {
            theme['topics'].forEach(topic => {
                let dataOrderStage = topic['stages'];
                // dataOrderStage['themes'] = topic['name'];
                newData['stages'].push(dataOrderStage);
            });

        });
        this.data = newData;
        return newData;
    }
    
    ;
}

