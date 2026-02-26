<template>
	<div>
		<div class="position-relative">
			<label
				:for="data.id"
				class="col-form-label"
				>{{ data.label }}:</label
			>
			<input
				:placeholder="data.placeholder"
				:id="data.id"
				class="form-control"
				@input="(e: any) => emitFunction(data.action, data, e.target.value)"
				v-model="data.value"
				type="text" />
			<a
				ref="btn_create"
				v-if="data.action && data.hasChild() && !data.value"
				class="icon-add icon-in-input"
				data-bs-toggle="collapse"
				href="#block-add"
				role="button"
				aria-expanded="false"
				aria-controls="block-add">
				<div class="icon-add-item icon-add-y"></div>
				<div class="icon-add-item icon-add-x"></div>
			</a>
			<span
				@click="() => clearSearchFull(data, false)"
				v-else
				:class="'icon-in-input icon-x d-' + display.show">
				<div class="icon-xy"></div>
				<div class="icon-xx"></div>
			</span>
		</div>
		<span
			v-if="data.msg_r"
			class="text-danger"
			>{{ data.msg }}</span
		>

		<ul
			v-if="data.child['options_search']"
			class="list-group list-group-bg list-event-request scroll">
			<li
				v-for="theme in data.get_all_child('options_search')"
				:key="theme.id"
				class="list-group-item"
				aria-current="true"
				@dblclick="emitFunction(theme.action, data, theme)">
				{{ theme.name }}
				<template v-if="data.tag == 'event_name'">
					<span
						v-if="theme.hasClass(display.show)"
						@click="
							() => {
								emitFunction('requestLTopic', theme);
								generateDisplay(theme);
							}
						"
						:class="'corner-more ' + theme.class"
						>⇲</span
					>
					<span
						@click="() => clearSearchFull(theme)"
						v-else
						:class="'icon-x icon-in-search ' + display.show">
						<div class="icon-xy"></div>
						<div class="icon-xx"></div>
					</span>
					<ul
						class="menu-border"
						v-if="theme.child['options_search']">
						<li
							v-for="option in theme.get_all_child('options_search')"
							:key="option.id"
							class="list-group-item scroll highlights-li position-relative"
							aria-current="true"
							@click="emitFunction(option.action, data, option)">
							{{ option.name }}
						</li>
					</ul>
				</template>
			</li>
		</ul>
		<div>
			<div
				id="block-add"
				:class="data.class"
				style="width: 18rem"
				v-if="data.hasChild() && !data.value">
				<div class="card-body">
					<!-- <h5 class="card-title">{{ data.get_child('main',0).label }}</h5> -->
					<form
						@submit.prevent="
							(e) =>
								emitFunction(data.get_child('main', 0).action, data, 'null')
						">
						<template
							v-for="input in data.get_child('main', 0).get_all_child()">
							<component
								v-bind:is="listComponents[input.type as InputTypeComponent]"
								:data="input"></component>
						</template>
						<div class="mt-3 flex">
							<button
								v-for="button in data
									.get_child('main', 0)
									.get_all_child('btn_main')"
								:class = "button.class"
								:type  = "button.type as InputTypeButton">
								{{ button.label }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<input
			v-if = "data.hasChild(undefined,'id_hidden')"
			hidden
			type="number"
			:name="data.get_child('id_hidden', 0).name"
			v-model="data.get_child('', 0, true).value" />
	</div>
</template>
<script lang="ts">
	import InputRadio from "./InputRadio.vue";
	import InputTextarea from "./InputTextarea.vue";
	import InputGeneric from "./InputGeneric.vue";
	import {Element} from "@/utils/Element.ts";
  import {InputTypeAll, InputTypeButton, InputTypeComponent} from "@/interface/ElementInterface.ts";
	export default {
		props: {data: {type: Element}},
		data() {
			return {
				display: {show: "d-block", none: "d-none"},
				listComponents: {
					date: InputGeneric,
					text: InputGeneric,
					number: InputGeneric,
					radio: InputRadio,
					textarea: InputTextarea,
				},
			};
		},
		methods: {
			generateDisplay(element: Element) {
				if (element.hasClass(this.display.show)) {
					element.set_class(this.display.none);
					element.drop_class(this.display.show);
					return;
				}

				element.drop_class(this.display.none);
				element.set_class(this.display.show);
			},

			clearSearchFull(element: Element, change = true) {
				change ? this.generateDisplay(element) : "";
				element.value = "";

				delete element.child.options_search;
			},

			emitFunction(
				methodName: string,
				domEvent: Element,
				data: any = undefined,
			) {
				this.$emit("e_function", methodName, domEvent, data);
			},
		},
		components: {InputRadio, InputTextarea, InputGeneric},
	};
</script>
