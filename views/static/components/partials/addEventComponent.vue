<template>
	<hr />

	<div class="d-flex gap-3 justify-content-center">
		<Button
			v-for="[key, value] in Object.entries(labels_form)"
			:id_element="key"
			:data_bs_target="'#add-' + key"
			:key="key">
			{{ value }}
		</Button>
	</div>
	<ModalComponent
		v-for="[key, instance] in Object.entries(formInstacesList)"
		@e_function="handleF"
		:element_data="instance.getStap()">
		<div class="nav-modal">
			<template v-for="stap in instance.stap_nav.get_all_child()">
				<div
					:class="stap.class"
					@click="instance.jump_stap(Number(stap.id))">
					{{ stap.value }}
				</div>
			</template>
		</div>
		<div class="modal-header">
			<h1
				class="modal-title fs-5"
				id="ModalLabel">
				{{ labels_form[key] }}
			</h1>
		</div>
	</ModalComponent>
</template>
<script lang="ts">
	import ModalComponent from "./modalComponent.vue";
	import Button from "./Button.vue";
	import {ManageStap} from "@/utils/ManageStap.ts";
	import {Element} from "@/utils/Element.ts";
	import {ApiClient} from "@/utils/request.js";
	import {DataAll, FormCore} from "@/utils/TypeElement.ts";

	type AllowedMethods = "nextStap" | "create";

	export default {
		data() {
			return {
				request: new ApiClient(location.href),
				requestList: {
					theme_name: "/match-event?name=",
					event_name: "/match-event?name=",
					event_topic_name: "/match-event-topic?name=",
					topic_name: "/match-event-topic?name=",
					tags: "/relationTable?id=",
					topics: "/relationTable?id=",
					form: "/form?",
				} as Record<string, string>,
				formInstacesList: {} as Record<string, ManageStap>,
				instaceManageStap: {} as ManageStap,
				url_form: {
					event: "form-event",
					theme: "form=theme",
					topic: "form=topic",
					stage: "form=stage",
					tag: "form=tag",
				} as Record<string, string>,
				labels_form: {
					event: "Novo Evento",
					theme: "Nova Temática",
					topic: "Novo Tópico",
					tag: "Nova Tag",
				} as Record<string, string>,
				fullMessage: this.message,
			};
		},
		created() {
			Object.keys(this.url_form).forEach((uri_key) => {
				this.get_form(uri_key);
			});
		},

		methods: {
			handleF(methodName: AllowedMethods, domEvent: Element, data: any) {
				//data.name = topic
				// let name                   = (domEvent.name).replace(/_\w+$/,'')
				let name = domEvent.parent_name;

				if (name) {
					this.instaceManageStap = this.formInstacesList[name];
				}

				this[methodName](domEvent, data);
			},
			nextStap() {
				this.instaceManageStap.getStap();
				this.instaceManageStap.next_stap();
			},
			next() {
				this.nextStap();
			},
			close() {
				let want_close = prompt("será limpo todos os dados, tem certeza");
				if (!want_close) return;
				//açao de limpar o form_element e os input values
			},

			async requestL(data: Element) {
				let key_request = data.name;
				let value_search = data.value;

				if (!key_request) return;

				value_search = String(value_search).trim();

				if (
					value_search.length < 3 ||
					!value_search ||
					!this.requestList[key_request]
				) {
					return;
				}

				let url = this.requestList[key_request] + value_search;

				// Agora o DOM está atualizado
				await this.request
					.get(url)
					.then((response: any) => {
						let children = data.for_data(
							response,
							"create_child",
							"options_search",
						);
						data.for_children(children, "set_action", "setEvent");
						data.for_children(children, "set_class", "d-block");
					})
					.catch((error) => {
						console.error("Error fetching timeline:", error);
					});
			},

			create(ins_element: Element, event: any) {
				this.next();

				if (this.instaceManageStap.stap_nav.has_error_child(0)) return;

				this.instaceManageStap.set_data_form();

				let data_v =
					this.instaceManageStap.element_parent.get_all_child("form_data");

				if (!data_v) return;
				// let form_data = new FormData(form);
				// const data = Object.fromEntries(form_data.entries());

				this.request
					.post("/event", data_v, {accept: "application/json"})
					.then((response: any) => {
						if (typeof event !== null) {
							data_v.forEach((el) => {
								el.child.main.forEach((input) => {
									input.value = "";
								});
							});
							return;
						}
						//collapse
						let el = document.querySelector("#block-add");

						if (el) {
							el.className = ins_element.class;
						}

						//set value request input
						let data = response[0];

						if (typeof data === null) return;
						if (typeof data.name === null) return;
						if (typeof ins_element === null) return;

						let child = ins_element
							.set_value(data.name)
							.get_child("id_hidden", 0);

						if (child) child.set_value(data.id);
					});
			},
			setEvent(inst_el: Element, data: Element) {
				let child = inst_el.set_value(data.name).get_child("id_hidden", 0);

				if (!child) return;

				child.set_value(data.id);
				inst_el.child["options_search"] = [];
			},
			get_form(name: string, args: any = null) {
				if (!this.url_form.hasOwnProperty("name")) return;

				let uri = this.url_form[name];
				let url = uri.search("=") == -1 ? uri : "form?" + uri;

				this.request.get("/" + url).then((response:any ) => {
					let name_form = "add-" + name;

					let instance = new ManageStap();
					instance.generateElement(response as Record<string, DataAll>, name_form);
					console.log(instance);
					this.formInstacesList[name] = instance;
				});
			},

			requestLTopic(data: Element) {
				this.request.get(`/themes?id=${data.id}/topics`).then(
					(response) => {
						let children = data.for_data(
							response.data,
							"create_child",
							"options_search",
						);
						data.for_children(children, "set_action", "setEvent");
				});
			},
		},

		components: {ModalComponent, Button},
	};
</script>
