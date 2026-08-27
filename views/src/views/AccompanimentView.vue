<style lang = "css" scoped src = "@/assets/style/scss/accompaniement.scss"></style>
<style lang = "css" src        = "@/assets/style/scss/modules/_iconContract.scss"></style>
<template>
	<div>
		<div style = "text-align: center; margin-bottom: 50px">
			<h1>Acompanhamento</h1>
		</div>
		<div class = "content-blocks">
			<div class = "content-data">
				<AcompaniementLeftComponent @dataFilter="manageData.setData($event)"></AcompaniementLeftComponent>

				<div class = "block-center">
					<NavGuides
					    @arrowAlter="manageData.orderBy($event)"
						:guides = "manageData.listTitles"
						:active = "manageData.orderActived">
					</NavGuides>
					<div      class = "block-main">
					<div v-for = "item in manageData.dataList" class = "data-item">
					<h4       class = "item-title">{{ item.name }}</h4>
							<div>
									<div class = "d-flex">
									<div class = "graphic">
											<Doughnut
												:id          = "'graphic_'+item.id"
												:settingsData = "dataChart" />
										</div>
										<div class = "icons-state">
											<BiAlarmFill  />
											<BiMicFill></BiMicFill>
											<HeroiconsOutlineExclaimationTriangle></HeroiconsOutlineExclaimationTriangle>
											<LaEyeSolid></LaEyeSolid>
											<!-- <LaEyeSlashSolid></LaEyeSlashSolid> -->
											<LaChevronRight></LaChevronRight>
											<MdiAccountEdit></MdiAccountEdit>
										</div>
									</div>
							</div>
							<button
								@click = "setBlockDataItem"
								class  = "btn"
								type   = "button">

								Show
							</button>
						</div>
						<div class = "data-item">
						<h4  class = "item-title">Item Title</h4>
							<div>
								<div class = "d-flex">
								<div class = "graphic">
										<Doughnut
												id          = "dot3"
											:settingsData = "dataChart1" />
									</div>
									<div class = "icons-state">
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
								class = "btn"
								@click="funcTeste"
								type  = "button">
								ativa
							</button>
						</div>
						
					</div>
				</div>
				<div               v-show = "dataItem" class      = "group-rigth">
				<div               v-show = "icon_display" :class = "'content-icon-arrow'+class_icon">
				<IconArrowContract @click = "arrowAlterRight"></IconArrowContract>
					</div>
					<AcompaniementRightComponent :c_arrow_right = "c_arrow_right" v-if              = "optionBlock == OptionBlock.rigth"></AcompaniementRightComponent>
					<ModalComponent              @hidden        = "()=>arrowAlterRight()" v-else-if = "optionBlock== OptionBlock.modal" :id = "dataItem"></ModalComponent>
				</div>
				
			</div>
		</div>
	</div>
</template>
<script setup lang = "ts">
	import {Element} from "@/utils/Element.ts";
	import NavGuides from "@/components/ui/NavGuides.vue";
	import Doughnut from "@/components/features/Doughnut.vue";
	import AcompaniementRightComponent from "@/components/features/AcompaniementRightComponent.vue";
	import AcompaniementLeftComponent from "@/components/features/AcompaniementLeftComponent.vue";
	import ModalComponent from "@/components/features/ModalComponent.vue";
	import {ChartTypeRegistry} from "chart.js";
	  //icons
	import BiAlarmFill from "~icons/bi/alarm-fill";
	import BiMicFill from "~icons/bi/mic-fill";
	import HeroiconsOutlineExclaimationTriangle from "~icons/heroicons-outline/exclaimation-triangle";
	import LaEyeSolid from "~icons/la/eye-solid";
	import LaChevronRight from "~icons/la/chevron-right";
	import LaEyeSlashSolid from "~icons/la/eye-slash-solid";
	import IconArrowContract from "@/components/ui/IconArrowContract.vue";
	import MdiAccountEdit from '~icons/mdi/account-edit';
	  //modal
	import {useTemplateRef} from 'vue'
	import {ref, computed, reactive} from 'vue'
	import type {BvTriggerableEvent} from 'bootstrap-vue-next'

	  //utils
	import {ManageData} from "@/utils/ManageData.ts";
import { isArray } from "chart.js/helpers";
	// import * as mix from '@/utils/functionsMixin.ts';
	
	function funcTeste(){
		console.log(manageData.orderCurrent);
		console.log(manageData.data);
	}
	const dataItem      = reactive(ref(false))
	const c_arrow_right = ref("");
	const icon_display  = ref(false);
	let e: OptionBlock
	const optionBlock     = ref(e);
	const class_icon      = ref('')
	let   dataFilter: any = ref();
	// A classe precisa estar dentro de um proxy reativo para que o template
	// seja atualizado quando orderActived for alterado pelos métodos.
	const manageData = reactive(new ManageData(dataFilter.value));
	// let guides_nav_active = ref(manageData.orderActived);

	  // import {type ComponentExposed} from 'vue-component-type-helpers'
	  // // let doughnutChart =  as HTMLCanvasElement;
	let type_doughnut: keyof ChartTypeRegistry = "doughnut";
	  // const myModal = useTemplateRef<ComponentExposed<typeof BModal>>('my-modal')
	  // const show = () => myModal.value?.show()

	let dataChart = {
		type: type_doughnut,
		data: {
			labels  : ["Red", "Blue", "Yellow"],
			datasets: [
				{
					label          : "My First Dataset",
					data           : [300, 50, 100],
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
			labels  : ["Red", "Blue", "Yellow"],
			datasets: [
				{
					label          : "My First Dataset",
					data           : [300, 50, 100],
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

	const nestedModal = (args?:keyof ActionBlock)=>{
		if(window.innerWidth>= 998 ) {
			blockRigth()
			return;
		};

		blockModal(args)
	}



	function setBlockDataItem(){
			dataItem.value = !dataItem.value;
			nestedModal()
	}

	function arrowAlterRight() {
		c_arrow_right.value = c_arrow_right.value == "close" ? "" : "close";

		if(optionBlock.value == OptionBlock.nothing){
			class_icon.value = ''

			nestedModal('icon')
			return
		}

		class_icon.value = ' icon-show'

		deactiveBlock()
	}

	function blockModal(args?: keyof ActionBlock){

		let actions:ActionBlock = {
			icon  : ()=>icon_display.value = !icon_display.value,
			hidden: ()=>{}
		}

		if(args){
			actions[args]()
		}

		optionBlock.value = OptionBlock.modal;
	};
	function blockRigth(){
		icon_display.value = true;
		optionBlock.value  = OptionBlock.rigth;
		console.log('cheguei')
		console.log(optionBlock)

	}

	function deactiveBlock(){
		optionBlock.value = OptionBlock.nothing;
		icon_display.value = true;

	}

	interface ActionBlock{
		icon():any
		hidden():any
	}

	

	enum OptionBlock{
		rigth='rigth',
		modal='modal',
		nothing='nothing'
	}
// 	const preventFn = (e: BvTriggerableEvent) => {
//   if (preventModal.value) e.preventDefault()
// }

</script>
