<template>
  <hr />

  <div class="d-flex gap-3 justify-content-center">
    <!-- <Button id_element="event" @button-click="get_form" data_bs_target="#add-event">
    Adicionar Evento
  </Button> -->
    <Button
      v-for="key in Object.keys(labels_form)"
      :id_element="key"
      :data_bs_target="'#add-' + key"
      :key="key"
    >
      {{ labels_form[key] }}
    </Button>
  </div>
  <ModalComponent
    v-for="[key, instance] in Object.entries(formInstacesList)"
    @e_function="handleF"
    :element_data="instance.getStap()"
  >
    <div class="nav-modal">
      <template v-for="stap in instance.stap_nav.get_child()">
        <div :class="stap.class" @click="instance.jump_stap(stap.id)">
          {{ stap.value }}
        </div>
      </template>
    </div>
    <div class="modal-header">
      <h1 class="modal-title fs-5" id="ModalLabel">{{ labels_form[key] }}</h1>
    </div>
  </ModalComponent>
</template>
<script>
import ModalComponent from "./modalComponent.vue";
import Button from "./Button.vue";
import {ManageStap} from "@/js/utils/ManageStap.js";
import { ApiClient } from "@/js/utils/request.js";

export default {
    data(){
      return{
          request: new ApiClient(location.href),
          requestList: {
            theme_name: "/match-event?name=",
            event_name: "/match-event?name=",
            event_topic_name: "/match-event-topic?name=",
            topic_name: "/match-event-topic?name=",
            tags: "/relationTable?id=",
            topics: "/relationTable?id=",
            form:'/form?',
          },
          formInstacesList:{},
          instaceManageStap:null,
          url_form:{
            event:'form-event',
            theme:'form=theme',
            topic:'form=topic',
            tag:'form=tag',
          },
          labels_form: {
            event:'Novo Evento',
            theme:'Nova Temática',
            topic:'Novo Tópico',
            tag:'Nova Tag',
          },
          fullMessage: this.message

      }
    },
    created(){
      Object.keys(this.url_form).forEach((uri_key)=> {
        this.get_form(uri_key);
      });
    },

    methods:{
      handleF(methodName, domEvent,data){//data.name = topic
        let name = (domEvent.name).replace(/_\w+$/,'')
        this.instaceManageStap = this.formInstacesList[name];
        this[methodName](domEvent,data)
      },
      nextStap(){
        // if(this.instaceManageStap.stap_index === 4){
        this.instaceManageStap.getStap();
        this.instaceManageStap.next_stap();
      },
      next() {
        this.nextStap();
      },
      close(){
        let want_close = prompt('será limpo todos os dados, tem certeza')
        if(!want_close)return;
        //açao de limpar o form_element e os input values
      },
      async requestL(data) {
        let key_request = data.name;
        let value_search = data.value

        value_search = String(value_search).trim();

        if ((value_search.length < 3 || !value_search) || !this.requestList[key_request]) {
          return;
        }

        let url = this.requestList[key_request] + value_search;

        // Agora o DOM está atualizado
        await this.request
        .get(url)
        .then((response) => {
          // let el = this.instaceManageStap.element.search_child(key_request)
          //
         
          let children = data.for_data(response,'set_child','options_search')
          data.for_children(children,'set_action')
        })
        .catch((error) => {
          console.error("Error fetching timeline:", error);
        });
      },

      create(ins_element, event) {
        this.next();

        if(this.instaceManageStap.stap_nav.has_error_child(0))return;

        this.instaceManageStap.set_data_form()

        let data_v = this.instaceManageStap.element_parent.get_child('form_data');
        // let form_data = new FormData(form);
        // const data = Object.fromEntries(form_data.entries());

        this.request
          .post("/event", data_v, { accept: "application/json" })
          .then((response) => {
            //collapse
            let el = document.querySelector("#block-add");
            el.className = ins_element.class;
            //set value request input
            let data = response[0];
            ins_element.set_value(data.name)
              .get_child("id_hidden", 0)
              .set_value(data.id);
          }
        )
      },
      setEvent(inst_el,data){
        inst_el.set_value(data.name)
          .get_child('id_hidden',0)
            .set_value(data.id)
        inst_el.child['options_search'] = []
      },
      get_form(name,button){
        let uri = this.url_form[name]
        let url = uri.search('=') == -1 ?uri:'form?'+uri;

        this.request.get('/'+url)
        .then(response => {
          let name_form = 'add-' + name;

          let instance = new ManageStap();
          instance.generateElement(response, name_form);
          console.log(instance);
          this.formInstacesList[name] = instance;

          // button.target.click();

        });
      },

      requestLTopic(data){
        this.request.get(`/themes?id=${data.id}/topics`)
          .then(response=>{
            console.log(`/themes?id=${data.id}/topics`)
            console.log(response)
          })
      }
    },

    components: { ModalComponent,Button }
}
</script>
