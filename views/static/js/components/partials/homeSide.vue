<template>
    <div class="content-side-blocks">
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
        <div v-if="data.media_averange" class="averange-data-block">
            <h3>EVENTOS</h3>
            <div class="averange-data-container">
                <div class="flex pointer">
                    <div id="amount_averange_id"><span>{{ data.media_averange.amount_event }}</span> </div>
                    <span>TOTAL</span>
                </div>
                <div class="flex pointer">
                    <span id="point_averange_id"> {{ data.media_averange.point_average }}% </span>
                    <span>PONTO MÉDIO</span>
                </div>
            </div>
        </div>
        <h2>Media de evolução</h2>

    </div>
</template>
<script>
    export default {
        props: ['data']
    }
</script>
