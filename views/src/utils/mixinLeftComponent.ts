import {ApiClient} from "@/utils/request";

const optionsForSearch: object[] = []

let keyOptions: string[] = [];
let emit      : Function;

function setEmitFunction(emitFunction: Function) {
    emit = emitFunction;
}
    
function makeRequestWhenChange(optionsForSearch: object[]) {
    let   request = new ApiClient(location.origin+'/reevolution')
    const params  = JSON.stringify(optionsForSearch);
      // Transformamos em string e depois codificamos para URL
    const dataString = encodeURIComponent(params);
    const url = `/filters-accompaniment?data=${dataString}`;
// params vira "status=ativo&pagina=1&limite=10"

    request.get(url)
    .then((response) => {
        emit('dataFilter', response.data);

    }).catch((error) => {
        console.error(error);
    });
}

function setFilterSearch(addTagFn: (tag: string) => void, event: InputEvent, option_dropdown_filter: { title: string,name: string }) {
    let inputElement = event.target as HTMLInputElement;
    let formatedOption = option_dropdown_filter.title+ ":" + inputElement.value

    optionsForSearch.push({'filterforTable': formatedOption})
    
    setTimeout(
        () => {
            addTagFn(formatedOption);
        }
        , 10
    );
    
    return makeRequestWhenChange(optionsForSearch)
}

function dropFilterSearch(dropTagfn: (tag: string)=>void, tag: string) {
    optionsForSearch.map((option: { filterforTable?: string }, index: number) => {
        if (option.hasOwnProperty('filterforTable') && option['filterforTable'] === tag) {
            optionsForSearch.splice(index, 1); // Break the loop
        }
    });
    dropTagfn(tag);
   
    setTimeout(
        () => {
        let input       = document.getElementById('tags-basic') as HTMLInputElement;
        input.value = ''

        }
        , 10
    );
    makeRequestWhenChange(optionsForSearch)
}

function setDataFilterSearch(key: string, event: InputEvent) {

    let inputElement = event.target as HTMLInputElement & { control?: HTMLInputElement };
    let inputValue   = inputElement.value;

    if (inputElement.control) {
       return; // Ignore if the input is part of a form control
    }

    if(keyOptions.includes(key)){
        let droppedOption: object = dropDataFilterSearch(key)[0];
        let keyDropped = key as keyof typeof droppedOption
        // keyOptions.splice(keyOptions.indexOf(key), 1);
        
        if(droppedOption[keyDropped] === inputValue){
            droppedOption        = droppedOption;
            setTimeout(() => {
                const input = (inputElement.control ?? inputElement) as HTMLInputElement;
                inputElement.checked = false;
            }, 10);

            if(optionsForSearch.length < 1)return emit('dataFilter', []);;

            makeRequestWhenChange(optionsForSearch)
            return;
            
        }

    }

    if(inputValue && !(inputValue === '')){
        optionsForSearch.push({[key]: inputValue});
         keyOptions.push(key);
    }

    if(optionsForSearch.length < 1)return emit('dataFilter', []);

    makeRequestWhenChange(optionsForSearch)
}

function dropDataFilterSearch(key: string) {
    let index = optionsForSearch.findIndex((option:object) => {
        if (option.hasOwnProperty(key)) {
            return true; // Break the loop
        }
        return false;
    });
    if (index === -1)return;

    keyOptions.splice(keyOptions.indexOf(key), 1);
    return optionsForSearch.splice(index, 1);
     
}

export default {setEmitFunction, makeRequestWhenChange, setFilterSearch, dropFilterSearch, setDataFilterSearch, dropDataFilterSearch};