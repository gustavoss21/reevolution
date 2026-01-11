<template>
  <hr />

  <button
    type="button"
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#add-event"
    data-bs-whatever="@getbootstrap"
  >
    Adicionar Evento
  </button>
  <ModalComponent       
    @e_function="handleF"
    :element_data="instanceStap.getStap()"
   >
    <div class="nav-modal">
      <template v-for="stap in instanceStap.stap_nav.get_child()">
        <div :class="stap.class" @click="instanceStap.jump_stap(stap.id)">
          {{ stap.value }}
        </div>
      </template>
    </div>
    <div class="modal-header">
      <h1 class="modal-title fs-5" id="ModalLabel">NOVO EVENTO</h1>
    </div>
  </ModalComponent>
</template>
<script>
import ModalComponent from "./modalComponent.vue";
import {ManageStap} from "@/js/utils/ManageStap.js";
import { ApiClient } from "@/js/utils/request.js";

export default {
    data(){
      return{
          request: new ApiClient(location.href),
          instanceStap:new ManageStap(),
          requestList: {
            theme_name: "/match-event?name=",
            topic_name: "/match-event-topic?name=",
            tags: "/relationTable?id=",
            topics: "/relationTable?id=",
            form:'/form?'
          },
          fullMessage: this.message
          
      }
    },
    created(){
          console.log('ADD monte');

      this.request.get('/form?create-event=stages')
        .then(response => {
          this.instanceStap.generateElement(response);
          // this.element_data = this.instanceStap.getStap()
          console.log(this.instanceStap);
        })
    },

    methods:{
      handleF(methodName, domEvent,data){
        console.log(methodName)
        this[methodName](domEvent,data)
      },
      nextStap(){
        // if(this.instanceStap.stap_index === 4){
        this.instanceStap.next_stap();
        console.log(this.fullMessage)
      },
      next() {
        this.nextStap();        
      },
      close(){
        let want_close = prompt('será limpo todos os dados, tem certeza')
        if(!want_close)return;
        //açao de limpar o form_element e os input values
      },
      async requestL(value_search, key_request) {
        value_search = String(value_search).trim();
        if ((value_search.length < 3 || !value_search) || !this.requestList[key_request]) {
          return;
        }

        let url = this.requestList[key_request] + value_search;

        // Agora o DOM está atualizado
        await this.request
        .get(url)
        .then((response) => {
          let el = this.instanceStap.element.search_child(key_request)

          el.for_data('set_child','options_search',response)
            .for_children('set_action','options_search','setEvent')
        })
        .catch((error) => {
          console.error("Error fetching timeline:", error);
        });
      },
      create(ins_element, event) {
        this.next();

        if(this.instanceStap.stap_nav.has_error_child(0))return;
        
        this.instanceStap.set_data_form()

        let data_v = this.instanceStap.element_parent.get_child('form_data');
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
    },

    components: { ModalComponent }
}
</script>
