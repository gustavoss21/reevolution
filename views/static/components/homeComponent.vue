<template>
	<div>
		<div>
			<AddEventComponent></AddEventComponent>
		</div>
		<div class="content-blocks">
			<div>
				<div class="block-container block-stage">
					<h2 class="content-theme">CRONOGRAMA PRINCIPAL</h2>

					<a
						v-for="event in timeline"
						href=""
						:key="'theme-' + event.id">
						<div
							class="theme-item"
							id="">
							<h2 class="theme-title"></h2>
							<h3 class="theme-topic"></h3>
							<div>
								<h4 class="title-item-stage data-stage">
									{{ event.topic.stage.name }}
								</h4>
							</div>
							<div>
								<span class="title-item">Prioridade:</span>
								<span class="title-item-stage-priority data-stage">
									{{ event.topic.stage.priority_op }}
								</span>
							</div>
							<div>
								<span class="title-item">Domínio:</span>
								<span
									class="title-item-stage-domain"
									data-stage
									>{{ event.topic.stage.domain_level }}</span
								>
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

					<a
						v-for="without_study_event in more_time_without_study_event"
						href=""
						:key="'theme-' + without_study_event.id">
						<div
							class="theme-item"
							id="">
							<h2 class="theme-title"></h2>
							<h3 class="theme-topic"></h3>
							<div>
								<h4 class="title-item-stage data-stage">
									{{ without_study_event.name }}
								</h4>
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

					<a
						v-for="event_priority in more_priority_events"
						href=""
						:key="'theme-' + event_priority.id">
						<div
							class="theme-item"
							id="">
							<h2 class="theme-title"></h2>
							<h3 class="theme-topic"></h3>
							<div>
								<h4 class="title-item-stage data-stage">
									{{ event_priority.name }}
								</h4>
							</div>
							<div>
								<span class="title-item">Prioridade:</span>
								<span class="title-item-stage-priority data-stage">{{
									event_priority.priority_op
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

<script lang="ts">
	import HomeSide from "./partials/homeSide.vue";
	import AddEventComponent from "./partials/addEventComponent.vue";
	import {ApiClient} from "@/utils/request.ts";
	import {
		EventType,
		PriorityEvent,
		WithoutStudyEvent,
	} from "@/interface/EventInterface.ts";

  import "bootstrap/dist/css/bootstrap.css";
  import "@/css/styleT.css";
 // Import Bootstrap and BootstrapVue3 CSS files (order is important)
           //   import "@/css/style.css";
 // import "@/css/evento.css";
	import {DataStatistic} from "@/utils/HomeType.ts";

	export default {
		data() {
			return {
				request: new ApiClient(location.href),
				timeline: [] as EventType[],
				more_time_without_study_event: [] as WithoutStudyEvent[],
				more_priority_events: [] as PriorityEvent[],
				data: {} as DataStatistic,
			};
		},
		// created() {
		// 	this.request
		// 		.get("/timeline")
		// 		.then((response) => {
		// 			this.timeline = response.data as EventType[];
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching timeline:", error);
		// 		});

		// 	this.request
		// 		.get("/status-averange")
		// 		.then((response) => {
		// 			this.data.media_averange = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching media_averange:", error);
		// 		});

		// 	this.request
		// 		.get("/time-without-study")
		// 		.then((response) => {
		// 			this.data.time_without_study = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching time_without_study:", error);
		// 		});

		// 	this.request
		// 		.get("/count-events-weekly")
		// 		.then((response) => {
		// 			this.data.count_events_weekly = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching count_events_weekly:", error);
		// 		});

		// 	this.request
		// 		.get("/averange-time-without-study")
		// 		.then((response) => {
		// 			this.data.averange_time_without_study = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching averange_time_without_study:", error);
		// 		});

		// 	this.request
		// 		.get("/date-time-without-study")
		// 		.then((response) => {
		// 			this.data.more_time_without_study = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching more_time_without_study:", error);
		// 		});

		// 	this.request
		// 		.get("/more-priority-events")
		// 		.then((response) => {
		// 			this.more_priority_events = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching more_priority_events:", error);
		// 		});

		// 	this.request
		// 		.get("/more-time-without-study-event")
		// 		.then((response) => {
		// 			this.more_time_without_study_event = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching more_time_without_study_event:", error);
		// 		});

		// 	this.request
		// 		.get("/total-quantity-each-tatus")
		// 		.then((response) => {
		// 			this.data.total_quantity_each_tatus = response.data;
		// 		})
		// 		.catch((error) => {
		// 			console.error("Error fetching total-quantity-each-tatus:", error);
		// 		});
		// },

		components: {AddEventComponent, HomeSide}, //
	};
</script>
