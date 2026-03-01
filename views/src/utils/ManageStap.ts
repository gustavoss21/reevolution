import {Element} from "./Element.ts";
import {DataType} from "./dataType.ts";
import translation from "./transletion.js";
import {DataAll, FormCore} from "@/utils/TypeElement.ts";
import {
	ElementInterface,
	ElementMethodsInterface,
	InputTypeAll,
	InputTypeComponent,
	InputTypeButton,
} from "views/types/interface/ElementInterface.ts";

export class ManageStap {
	stap_index?: number = undefined;
	element: Element;
	element_parent = new Element({name: "parent"});
	stap?: Element;
	staps: Element[];
	stap_nav = new Element({name: "stap"});
	stap_nav_index = 0;
	data_children: Record<string, DataAll> = {};
	element_rest?: DataAll;
	id = "";
	static elements_conditional: DataAll[] = [];

	getStap(): Element | undefined {
		let element: Element = this.staps[this.stap_index as number];

		if (!element) {
			console.error("fun:getStap -> Elemento nao definido");
			return undefined;
		}

		this.element = element;

		return element;
	}

	setElement() {
		this.element = this.staps[this.stap_index as number];
	}

	next_stap() {
		let index = (this.stap_index as number) + 1;
		this.navigate_to_stap(index, false);
	}

	jump_stap(index: number) {
		this.navigate_to_stap(index, false);
	}

	setNav(newIndex: number, oldIndex: number, start = true) {
		this.stap_nav
			.get_child("main", newIndex)
			.set_class("nav-item-modal active-modal");

		this.stap = this.stap_nav.get_child("main", newIndex);

		if (oldIndex == newIndex && start) return;

		let stap_old = this.stap_nav
			.get_child("main", oldIndex)
			.set_class("nav-item-modal");

		if (this.is_invalid()) {
			stap_old
				.set_class("nav-item-modal nav-error-item")
				.input_error(this.stap_nav);
			return;
		}

		stap_old.set_class("nav-item-modal nav-success-item");
		stap_old.success(this.stap_nav);
	}

	createStpas(active = false) {
		let index = this.stap_nav_index;
		this.stap_nav_index += 1;
		this.stap_nav
			.create_child()
			.set_name("stap")
			.set_class("nav-item-modal")
			.set_id(index)
			.set_value(this.stap_nav_index)
			.input_error(this.stap_nav);
	}

	generateElement(data: Record<string, DataAll>, id: string) {
		this.element_parent = new Element({
			name: "parent",
			id: id.replace("add-", ""),
		});
		this.staps = [];
		this.stap_nav = new Element({name: "stap"});
		this.stap_nav_index = 0;
		this.data_children = {};
		this.element_rest = undefined;
		let all_data_form = this.filterRawData(data);
		let element = null;
		let form_labels: Record<string, string>;
		let lastKey = "";
		this.id = id;

		if (!all_data_form) return;

		all_data_form.forEach((key) => {
			let form_data: FormCore[] = data[key]["data"];
			form_labels = data[key]["labelS"];
			let data_condition = form_labels["_condition"];

			//insert input by label
			this.setEvent(form_data, form_labels);
			let element = this.createElement(key);
			element = this.setElementData(form_data, form_labels, element);

			if (data_condition)
				this.setElementConditionalTrigger(element, data_condition);

			//set data from last element rest
			if (this.element_rest) {
				let form_data = this.element_rest.data;
				let form_labels = this.element_rest.labelS;

				this.setElementData(form_data, form_labels, element);
				this.element_rest = undefined;
			}
		});

		this.navigate_to_stap();
	}

	filterRawData(data: Record<string, DataAll>) {
		let data_keys = Object.keys(data);

		return data_keys.filter((k) => {
			let index = k.search("_child");

			if (index !== -1) {
				let key_c = k.slice(0, index);
				this.data_children[key_c] = data[k];
				return;
			}
			return true;
		});
	}

	/**
	 * @param {Array} form_data -> form data array
	 * @param {object} form_labels -> object with form labels
	 * @param {Element} element -> father element
	 * @param {boolean} pagination -> template with pagination
	 * @returns {Element} element is formated
	 */
	setElementData(
		form_data: FormCore[],
		form_labels: Record<string, string>,
		element: Element,
		pagination = true,
	) {
		let key_label_list = Object.keys(form_labels);
		let inputs_length = form_data.length; // nao deveria ter um -1
		let pathern_id = /.*_id$/;
		let pathern_r = /.+_op_.+/;
		let columns_radio = key_label_list.filter((val_d) => {
			return pathern_r.test(val_d);
		});

		form_data = this.re_index_title(form_data);

		for (let index = 0; index < inputs_length; index++) {
			form_data = this.re_index_description(form_data, index);
			let el = form_data[index];
			let column_name = el.COLUMN_NAME;
			let column_label = form_labels[column_name];
			let column_type = DataType[el.DATA_TYPE];
			let pathern_radio = new RegExp(`${column_name}.+`);
			let column_radio_filted = columns_radio.filter((v) =>
				pathern_radio.test(v),
			);

			if (!column_label) continue;

			let element_c = element
				.create_child()
				.set_label(column_label)
				.set_name(column_name)
				.set_id(column_name)
				.set_type(InputTypeAll[column_type] as InputTypeAll)
				.set_value(el.COLUMN_DEFAULT)
				.set_parent_name(element.name || "")
				.set_require(el.IS_NULLABLE);
			element_c.tag = column_name;

			//radio
			if (column_radio_filted.length > 0) {
				this.elementRadio(element_c, column_radio_filted, form_labels);
			}
			//request
			else if (column_type === "number" && pathern_id.exec(column_name)) {
				this.elementSearch(element_c, form_labels, column_name);
			}

			let element_child_legth = element.get_all_child().length;
			let required_next_step =
				pagination &&
				element_child_legth >= 4 &&
				// (Number.isInteger((index + 1) / 4)
				inputs_length - index + 1 >= 3;

			if (required_next_step) {
				if (inputs_length - 1 <= index + 3) {
					this.element_rest = {
						data: form_data.slice(index + 1),
						labelS: form_labels,
					};
					return element;
				}
				element = this.createElement(element.name as string);
			}
		}

		return element;
	}
	createElement(
		el_name: string,
		is_active = false,
		button_submit = false,
		data = {},
	) {
		let element = this.element_parent
			.create_child("main", data)
			.set_id(this.id)
			.set_action("create")
			.set_parent_name(this.element_parent.id as string);

		this.staps.push(element as never);
		this.setButton(element, button_submit);
		this.createStpas(is_active);
		element.set_name(el_name);
		return element;
	}
	setButton(element: Element, submit = false) {
		let button = element.get_child("btn_main", 1);
		// element.type extends
		if (!button) {
			let btn_closed = element
				.create_child("btn_main")
				.set_label("Fechar")
				.set_type(InputTypeButton.button)
				.set_class("btn btn-secondary")
				.set_action("close");

			button = element
				.create_child("btn_main")
				.set_label("Próximo")
				.set_type(InputTypeButton.submit)
				.set_name("submit")
				.set_class("btn btn-primary")
				.set_action("next")
				.set_dismiss(false);
		}

		if (submit) {
			let children = element.get_all_child("btn_main");
			element.for_children(children, "set_dismiss", true);
			button.set_label("Criar").set_action("criar");
		}
	}

	navigate_to_stap(index = 0, start = true) {
		if (index < 0 || index >= this.staps.length) {
			index = 0;
		}
		if (this.stap_index == undefined) {
			this.stap_index = index;
		}
		this.setNav(index, this.stap_index, start);
		this.stap_index = index;
		this.setElement();

		if (!this.stap_nav.has_error_child(1)) {
			this.setButton(this.element as Element, true);
		}
	}

	re_index_description(data: FormCore[], index: number) {
		if (!data[index] || !(data[index].DATA_TYPE === "text")) return data;
		let index_ele = index + 1;
		let division_result = index_ele / 4;
		let lenght_data = data.length;

		if (lenght_data < 4 || lenght_data - 4 <= 3) {
			return this.re_index_data(data, lenght_data - 1, index);
		}

		if (Number.isInteger(division_result)) return data;

		let division_int = Number.parseInt(String(division_result));
		let division_rest = index_ele % 4;
		let division_rest_for_int = 4 - division_rest;
		let new_index =
			division_int * 4 + division_rest + division_rest_for_int - 1;

		return this.re_index_data(data, new_index, index);
	}

	re_index_data(data: FormCore[], new_i: number, old_id: number) {
		if (!data[new_i]) return data;
		if (new_i == old_id) return data;

		let value = data[old_id];
		data[old_id] = data[new_i];
		data[new_i] = value;

		return data;
	}

	re_index_title(data: FormCore[]) {
		let index = data.findIndex((column) => column.COLUMN_NAME === "name");
		if (index == -1) return data;

		return this.re_index_data(data, 0, index);
	}

	is_invalid() {
		let element_data = this.element as Element;
		let children = element_data.get_all_child();
		let is_invalid = false;
		children.forEach((child) => {
			let value = child.value;
			let name = child.name as string;

			if (!value && child.require) {
				child.input_error(element_data);
				is_invalid = true;
				this.element_parent._set_message_parent(name);
				return;
			}

			child.success(element_data);
			this.element_parent._set_message_parent(name);
		});

		return is_invalid;
	}

	set_data_form() {
		this.element_parent.child["form_data"] = [];
		let children = this.element_parent.get_all_child();

		children.forEach((parent) => {
			let search_parent = this.element_parent.search_child(
				parent.name as string,
				"form_data",
			);

			if (!search_parent) {
				search_parent = this.element_parent
					.create_child("form_data")
					.set_name(parent.name as string);
			}

			let children = parent.get_all_child();

			children.forEach((element) => {
				this.set_input_data_form(search_parent, element);
			});
		});
	}

	set_input_data_form(parent: Element, element: Element) {
		let child = parent.create_child("main", element);

		if (element.type == "request") {
			let new_child = element.get_child("id_hidden", 0);
			child.set_value(new_child.value);
			child.set_id(new_child.id as string);
		}

		return child;
	}

	setEvent(form_data: FormCore[], form_labels: Record<string, Object>) {
		if (!form_labels["_event"]) return;

		let data = form_labels["_event"] as FormCore[];

		data.forEach((form_full) => {
			form_labels[form_full["COLUMN_NAME"]] = form_full["label"];
			form_full["label"] = "";
			form_full["tag"] = true;
			form_data.push(form_full);
		});
	}

	elementRadio(
		element_c: Element,
		columns_radio: string[],
		form_labels: Record<string, string>,
	) {
		element_c.set_type("radio" as InputTypeAll);

		for (let label_key of columns_radio) {
			element_c
				.create_child()
				.set_label(form_labels[label_key])
				.set_value(label_key)
				.set_id(label_key)
				.set_name(label_key)
				.set_parent_name(element_c.parent_name as string);
		}
	}
	elementSearch(
		element_c: Element,
		form_labels: Record<string, string>,
		column_name: string,
	) {
		let name_base = column_name.replace(/(.+)_id$/, "$1");
		let data_child = this.data_children[column_name];

		element_c
			.set_name(name_base + "_name")
			.set_id(name_base + "_name")
			.set_class("card collapse")
			.set_action("requestL")
			.set_placeholder("escreva e selecione o ...")
			.set_type("request" as InputTypeAll)

			.create_child("id_hidden")
			.set_name(column_name)
			.set_id(column_name)
			.set_parameter("hidden")
			.set_parent_name(column_name);

		element_c.tag = name_base + "_name";

		if (element_c.tag) {
			element_c.data_child["action"] = "requestLTopic";
		}

		if (!data_child) return;

		let title = "criar " + form_labels[column_name];
		let form_event_create = element_c
			.create_child()
			.set_label(title)
			.set_action("create")
			// .set_type("collapse")
			.set_type("request" as InputTypeAll);

		let el = this.setElementData(
			data_child["data"],
			data_child["labelS"],
			form_event_create,
			false,
		);

		this.setButton(el, true);
	}

	setElementConditionalTrigger(element: Element, data: any) {
		let [inputName, elTriggerName] = data["conditioner"].split(".");
		let elementWithTrigger = element.search_child(elTriggerName);
		let conditional = data["conditioned"];
		if (!conditional) return;

		// criar um gatilho

		//set data for form_data with conditional
	}

	eventElementConditional(input: Element, value: any) {
		// get elemet conditined
		// verificar se o valor bate com o esperado
		//run function to create elements
	}
	// cria os elementos de fato
	createElementConditional(
		form_data: FormCore[],
		form_labels: Record<string, Object>,
	) {
		if (!form_labels["_conditional"]) return;

		let data = form_labels["_conditional"];
	}
}
