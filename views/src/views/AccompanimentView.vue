<style lang="css" scoped src="@/assets/style/scss/accompaniement.scss"></style>
<template>
	<div>
		<div style="text-align: center; margin-bottom: 50px">
			<h1>Acompanhamento</h1>
		</div>
		<div class = "content-blocks">
		<div class = "content-data">
				<AcompaniementLeftComponent></AcompaniementLeftComponent>
				

				<div class="block-center">
					<NavGuides
						:guides="guides_nav"
						:active="guides_nav[0]">
					</NavGuides>
					<div class="block-main">
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot1"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot2"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot3"
											:settingsData="dataChart1" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot4"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot5"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot6"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot7"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot8"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot10"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot12"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot9"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot11"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot13"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot14"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot15"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot16"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
						<div class="data-item">
							<h4 class="item-title">Item Title</h4>
							<div>
								<div class="d-flex">
									<div class="graphic">
										<Doughnut
											id="dot17"
											:settingsData="dataChart" />
									</div>
									<div class="icons-state">
										<BiAlarmFill  />
										<BiMicFill></BiMicFill>
										<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
										<LaEyeSolid></LaEyeSolid>
										<LaChevronRight></LaChevronRight>
										<LaEyeSlashSolid></LaEyeSlashSolid>
									</div>
								</div>
							</div>
							<button
								class="btn"
								type="button">
								Show
							</button>
						</div>
					</div>
				</div>
				<AcompaniementRightComponent></AcompaniementRightComponent>
			</div>
		</div>
	</div>
</template>
<script setup lang="ts">
	import NavGuides from "@/components/ui/NavGuides.vue";
	import Doughnut from "@/components/features/Doughnut.vue";
	import AcompaniementRightComponent from "@/components/features/AcompaniementRightComponent.vue";
	import AcompaniementLeftComponent from "@/components/features/AcompaniementLeftComponent.vue";
	import {ChartTypeRegistry} from "chart.js";
	//icons
	import BiAlarmFill from "~icons/bi/alarm-fill";
	import BiMicFill from "~icons/bi/mic-fill";
	import HeroiconsOutlineExclaimationTriangle from "~icons/heroicons-outline/exclaimation-triangle";
	import LaEyeSolid from "~icons/la/eye-solid";
	import LaChevronRight from "~icons/la/chevron-right";
	import LaEyeSlashSolid from "~icons/la/eye-slash-solid";
	// let doughnutChart =  as HTMLCanvasElement;
	let type_doughnut: keyof ChartTypeRegistry = "doughnut";

	let dataChart = {
		type: type_doughnut,
		data: {
			labels: ["Red", "Blue", "Yellow"],
			datasets: [
				{
					label: "My First Dataset",
					data: [300, 50, 100],
					backgroundColor: [
						"rgb(255, 99, 132)",
						"rgb(54, 162, 235)",
						"rgb(255, 205, 86)",
					],
					hoverOffset: 4,
				},
			],
		},
	};

	let dataChart1 = {
		type: type_doughnut,
		data: {
			labels: ["Red", "Blue", "Yellow"],
			datasets: [
				{
					label: "My First Dataset",
					data: [300, 50, 100],
					backgroundColor: [
						"rgb(255, 99, 132)",
						"rgb(54, 162, 235)",
						"rgb(255, 205, 86)",
					],
					hoverOffset: 4,
				},
			],
		},
	};



	let   guides_nav    = ["Tema", "Topico", "Categoria", "Estágio"];
</script>
