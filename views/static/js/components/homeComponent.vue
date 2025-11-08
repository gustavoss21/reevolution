<template>
  <div>
    <div class="content-blocks">
        <div>
            <div class="block-container block-stage">
                <h2 class="content-theme">CRONOGRAMA PRINCIPAL</h2>

                <a v-for="event in timeline" href="" :key="'theme-' + event.id">
                <div class="theme-item" id="">
                    <h2 class="theme-title"></h2>
                    <h3 class="theme-topic"></h3>
                    <div>
                    <h4 class="title-item-stage data-stage">{{ event.topic.stage.name }}</h4>
                    </div>
                    <div>
                    <span class="title-item">Prioridade:</span>
                    <span class="title-item-stage-priority data-stage">
                        {{event.topic.stage.priority}}
                    </span>
                    </div>
                    <div>
                    <span class="title-item">Domínio:</span>
                    <span class="title-item-stage-domain" data-stage>{{
                        event.topic.stage.domain_level
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Estatus:</span>
                    <span class="title-item-stage-status data-stage">{{
                        event.topic.stage.status
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Pontuação:</span>
                    <span class="title-item-stage-score data-stage">{{
                        event.topic.stage.score
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Ultima atualização: </span>
                    <data class="title-item-stage-updated_at data-stage">{{
                        event.topic.stage.upadated_at
                    }}</data>
                    </div>
                </div>
                </a>
            </div>
            <div class="block-container block-stage">
                <h2 class="content-theme">MAIS TEMPO SEM ESTUDO</h2>

                <a v-for="without_study_event in more_time_without_study_event" href="" :key="'theme-' + without_study_event.id">
                <div class="theme-item" id="">
                    <h2 class="theme-title"></h2>
                    <h3 class="theme-topic"></h3>
                    <div>
                    <h4 class="title-item-stage data-stage">{{ without_study_event.name }}</h4>
                    </div>
                    <div>
                    <span class="title-item">Prioridade:</span>
                    <span class="title-item-stage-priority data-stage">{{
                        without_study_event.priority
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Domínio:</span>
                    <span class="title-item-stage-domain data-stage">{{
                        without_study_event.domain_level
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Estatus:</span>
                    <span class="title-item-stage-status data-stage">{{
                        without_study_event.status
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Pontuação:</span>
                    <span class="title-item-stage-score data-stage">{{
                        without_study_event.score
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Ultima atualização: </span>
                    <data class="title-item-stage-updated_at data-stage">{{
                        without_study_event.upadated_at
                    }}</data>
                    </div>
                </div>
                </a>
            </div>
            <div class="block-container block-stage">
                <h2 class="content-theme">MAIS IMPORTANTES</h2>

                <a v-for="event_priority in more_priority_events" href="" :key="'theme-' + event_priority.id">
                <div class="theme-item" id="">
                    <h2 class="theme-title"></h2>
                    <h3 class="theme-topic"></h3>
                    <div>
                    <h4 class="title-item-stage data-stage">{{ event_priority.name }}</h4>
                    </div>
                    <div>
                    <span class="title-item">Prioridade:</span>
                    <span class="title-item-stage-priority data-stage">{{
                        event_priority.priority
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Domínio:</span>
                    <span class="title-item-stage-domain data-stage">{{
                        event_priority.domain_level
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Estatus:</span>
                    <span class="title-item-stage-status data-stage">{{
                        event_priority.status
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Pontuação:</span>
                    <span class="title-item-stage-score data-stage">{{
                        event_priority.score
                    }}</span>
                    </div>
                    <div>
                    <span class="title-item">Ultima atualização: </span>
                    <data class="title-item-stage-updated_at data-stage">{{
                        event_priority.upadated_at
                    }}</data>
                    </div>
                </div>
                </a>
            </div>
        </div>
      
       <HomeSide :data="data"></HomeSide>
    </div>
  </div>
</template>

<script>
import HomeSide from "./partials/homeSide.vue";
import { ApiClient } from "@/js/utils/request.js";
import "@/css/evento.css";

export default {
  data() {
    return {
      seen: true,
      teste: "testes",
      request: new ApiClient(location.href),
      timeline: [],
      more_time_without_study_event: [],
      more_priority_events: [],
      data: {},
    };
  },
  created() {
    this.request
      .get("/timeline")
      .then((response) => {
        this.timeline = response;
        console.log("this.timeline");
        console.log(this.timeline);
      })
      .catch((error) => {
        console.error("Error fetching timeline:", error);
      });

    this.request
      .get("/status-averange")
      .then((response) => {
        this.data.media_averange = response;
        console.log("this.media_averange");
        console.log(this.media_averange);
      })
      .catch((error) => {
        console.error("Error fetching media_averange:", error);
      });

    this.request
      .get("/time-without-study")
      .then((response) => {
        this.data.time_without_study = response;
        console.log("this.time_without_study");
        console.log(this.time_without_study);
      })
      .catch((error) => {
        console.error("Error fetching time_without_study:", error);
      });

    this.request
      .get("/count-events-weekly")
      .then((response) => {
        this.data.count_events_weekly = response;
        console.log("this.count_events_weekly");
        console.log(this.count_events_weekly);
      })
      .catch((error) => {
        console.error("Error fetching count_events_weekly:", error);
      });

    this.request
      .get("/averange-time-without-study")
      .then((response) => {
        this.data.averange_time_without_study = response;
        console.log("this.averange_time_without_study");
        console.log(this.count_events_weekly);
      })
      .catch((error) => {
        console.error("Error fetching averange_time_without_study:", error);
      });

    this.request
      .get("/date-time-without-study")
      .then((response) => {
        this.data.more_time_without_study = response;
        console.log("this.more_time_without_study");
        console.log(this.count_events_weekly);
      })
      .catch((error) => {
        console.error("Error fetching averange_time_without_study:", error);
      });

    this.request
      .get("/date-time-without-study")
      .then((response) => {
        this.more_time_without_study = response;
        console.log("this.more_time_without_study");
        console.log(this.count_events_weekly);
      })
      .catch((error) => {
        console.error("Error fetching more_time_without_study:", error);
      });

    this.request
      .get("/more-priority-events")
      .then((response) => {
        this.more_priority_events = response;
        console.log("this.more_priority_events");
      })
      .catch((error) => {
        console.error("Error fetching more_priority_events:", error);
      });

    this.request
      .get("/more-time-without-study-event")
      .then((response) => {
        this.more_time_without_study_event = response;
        console.log("this.more_time_without_study_event");
      })
      .catch((error) => {
        console.error("Error fetching more_time_without_study_event:", error);
      });

    this.request
      .get("/total-quantity-each-tatus")
      .then((response) => {
        this.data.total_quantity_each_tatus = response;
        console.log("this.total-quantity-each-tatus");
      })
      .catch((error) => {
        console.error("Error fetching total-quantity-each-tatus:", error);
      });
  },

  components: { HomeSide },
};
</script>
