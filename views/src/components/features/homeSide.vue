<template>
    <div class="side-block block-container">
        <div class="content-side-block">
            <div v-if="data.total_quantity_each_tatus">
                <div v-if="data.media_averange" class="block-status">
                    <h3>EVENTOS</h3>
                    <div class="averange-data-container">
                        <div class="flex pointer">
                            <div class="data-status" id="amount_averange_id"><span>{{ data.media_averange.amount_event }}</span> </div>
                            <span>TOTAL</span>
                        </div>
                        <div class="flex pointer">
                            <span class="data-status" id="point_averange_id"> {{ data.media_averange.point_average }} </span>
                            <span>PONTO MÉDIO</span>
                        </div>
                    </div>
                </div>
                <div>
                    <ChartsComponent id="status-pizza-charts" type="doughnut" :chartData="data.total_quantity_each_tatus"  :colors="COLORS"></ChartsComponent>
                </div>
            </div>
            <div v-if="data.time_without_study" class="averange-data-block">
                <h3>TEMPO SEM ESTUDO</h3>
                <date class="time-whitout-study format-data">{{ data.time_without_study }}</date>
            </div>
            <div v-if="data.count_events_weekly">
                <h3>EVENTOS NA SEMANA</h3>
                <div class="amaunt-events format-data">{{data.count_events_weekly}}</div>
            </div>
            <div v-if="data.averange_time_without_study" class="averange-data-block">
                <h3>TEMPO MÉDIO SEM ESTUDO</h3>
                <date class="time-averange-without-study format-data">{{data.averange_time_without_study}}</date>
            </div>
            <div v-if="data.media_averange" class="averange-data-block">
                <h3>NIVEL MEDIO SEM ESTUDO</h3>
            </div>

        </div>
    </div>
</template>
<script>
    import "https://cdn.jsdelivr.net/npm/chart.js"
    import ChartsComponent  from "./chartsComponent.vue";
    export default {
        props: ['data'],
        data() {
            return {
                COLORS : {
                    0 : 'rgb(54, 162, 235)',
                    1:'rgb(255, 99, 132)',
                    2:'rgb(255, 205, 86)',
                }
            }
        },
        components: { ChartsComponent},

    
    }
</script>
