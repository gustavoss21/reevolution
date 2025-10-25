

<template>
    <div>
        <div class="content-blocks">
            <div class="block-container">
            <h2 class="content-theme">Timeline de eventos</h2>

                <a v-for="event in timeline"  href="" :key="'theme-'+event.id">
                    <div class="theme-item" id="">
                        <h2 class="theme-title"></h2>
                            <h3 class="theme-topic"></h3>
                                <div>
                                    <span class="title-item">Estagio:</span>
                                    <span class="title-item-stage">{{event.topic.stage.name}}</span>
                                </div>
                                <div>
                                    <span class="title-item">Prioridade:</span>
                                    <span class="title-item-stage-priority">{{event.topic.stage.priority}}</span>
                                </div>
                                <div>
                                    <span class="title-item">Domínio:</span>
                                    <span class="title-item-stage-domain">{{event.topic.stage.domain_level}}</span>
                                </div>
                                <div>
                                    <span class=" title-item">Estatus:</span>
                                    <span class="title-item-stage-status">{{event.topic.stage.status}}</span>
                                </div>
                                <div>
                                    <span class="title-item">Pontuação:</span>
                                    <span class="title-item-stage-score">{{event.topic.stage.score}}</span>
                                </div>
                                <div>
                                    <span class="title-item">Ultima atualização: </span>
                                    <data class="title-item-stage-updated_at">{{event.topic.stage.upadated_at}}</data>
                                </div>
                    </div>
                </a>
            </div>
            <HomeSide :data="data"></HomeSide>
        </div>
    </div>
</template>

<script>
    import HomeSide from "./partials/homeSide.vue";
    import ChartsComponent from "./partials/chartsComponent.vue";
    import { ApiClient } from "@/js/utils/request.js";
    import "@/css/evento.css";

    export default {

        data() {
            return {
                seen: true,
                teste: 'testes',
                request: new ApiClient(location.href),
                timeline: [],
                data:{}

            }
        },
        created() {
            this.request.get('/timeline')
                .then((response) => {
                    this.timeline = response;
                    console.log('this.timeline');
                    console.log(this.timeline);
                })
                .catch((error) => {
                    console.error('Error fetching timeline:', error);
                });
            
            this.request.get('/status-averange')
                .then((response) => {
                    this.data.media_averange = response;
                    console.log('this.media_averange');
                    console.log(this.media_averange);
                })
                .catch((error) => {
                    console.error('Error fetching media_averange:', error);
                });
            
            this.request.get('/time-without-study')
                .then((response) => {
                    this.data.time_without_study =  response;
                    console.log('this.time_without_study');
                    console.log(this.time_without_study);
                })
                .catch((error) => {
                    console.error('Error fetching time_without_study:', error);
                });

            this.request.get('/count-events-weekly')
            .then((response) => {
                this.data.count_events_weekly =  response;
                console.log('this.count_events_weekly');
                console.log(this.count_events_weekly);
            })
            .catch((error) => {
                console.error('Error fetching count_events_weekly:', error);
            });

            this.request.get('/averange-time-without-study')
            .then((response) => {
                this.data.averange_time_without_study =  response;
                console.log('this.averange_time_without_study');
                console.log(this.count_events_weekly);
            })
            .catch((error) => {
                console.error('Error fetching averange_time_without_study:', error);
            });

            this.request.get('/more-time-without-study')
            .then((response) => {
                this.data.more_time_without_study =  response;
                console.log('this.more_time_without_study');
                console.log(this.count_events_weekly);
            })
            .catch((error) => {
                console.error('Error fetching averange_time_without_study:', error);
            });

            this.request.get('/more-time-without-study')
            .then((response) => {
                this.more_time_without_study =  response;
                console.log('this.more_time_without_study');
                console.log(this.count_events_weekly);
            })
            .catch((error) => {
                console.error('Error fetching more_time_without_study:', error);
            });

            
            this.request.get('/more-priority-events')
            .then((response) => {
                this.data.more_priority_events =  response;
                console.log('this.more_priority_events');
            })
            .catch((error) => {
                console.error('Error fetching more_priority_events:', error);
            });
        },

        components: { HomeSide,ChartsComponent }
        
    }
</script>
