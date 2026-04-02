<template>
    <div class = "content-data">
        <h5>Gráficos</h5>
        <div class = "full-width">
            <div>
                <h6       class = "txta-center">Acompanhamento da semana</h6>
                <div      class = "graphic-right">
                <div      class = "txta-center">
                <Doughnut id    = "graphic-right-current" :settingsData = "dataChart"></Doughnut>
                        <label>Passada</label>
                    </div>
                    <div      class = "txta-center">
                    <Doughnut id    = "graphic-right-past" :settingsData = "dataChart"></Doughnut>
                        <label>atual</label>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                 <FullCalendar :options = "calendarOptions" />
            </div>
            
        </div>
    </div>
</template>
<script setup lang = "ts">
    import Doughnut from "@/components/features/Doughnut.vue";
	import {ChartTypeRegistry} from "chart.js";
    import FullCalendar from '@fullcalendar/vue3';
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'

    import esLocale from '@fullcalendar/core/locales/es';

 let type_doughnut: keyof ChartTypeRegistry = "doughnut";
 let calendarOptions                        = {
        plugins    : [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap',
        events     : [
          { title: 'event 1', date: '2026-03-01' },
          { title: 'event 2', date: '2026-03-02' }
        ],
          // eventTextColor:'black',
        eventColor   : '#378006',
        Object       : {},
        locale       : 'pt-br',
        headerToolbar: {
                start : 'title',     // will normally be on the left. if RTL, will be on the right
                center: 'today',
                end   : 'prev,next'  // will normally be on the right. if RTL, will be on the left
        },
          // headerToolbar: { center: 'dayGridMonth,timeGridWeek' },
        
            timeGridWeek: { // name of view
            titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
 
      }
    }

    let dataChart = {
		type: type_doughnut,
		data: {
			labels  : ["Blue"],
			datasets: [
				{
					label          : "My First Dataset",
					data           : [70, 30],
					backgroundColor: [
						"rgb(255, 99, 132)",
						"rgb(54, 162, 235)"
					],
					hoverOffset: 4,
				},
			],
		},
	};

</script>
<style lang        = "scss" src = "@/assets/style/scss/modules/_calendar.scss"></style>
<style scoped lang = "scss" src = "@/assets/style/scss/modules/_aconpaniement_data_right.scss"></style>
