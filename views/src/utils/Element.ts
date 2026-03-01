import {handleError, Static} from "vue";
import {
	ElementInterface,
	InputTypeAll,
	StatusMessage,
	InputTypeComponent,
	InputTypeButton,
} from "views/types/interface/ElementInterface";

export class Element implements ElementInterface {
	id?: string;
	class: string = "";
	name: string;
	value: any;
	type: InputTypeComponent | InputTypeButton | InputTypeAll;
	label?: string;
	action?: string;
	dismiss?: boolean;
	require?: boolean;
	placeholder?: string;
	parameter?: string;
	parent_name?: string;
	tag: string = "";
	child: Record<string, Element[]> = {};
	data_child: any = {};
	msg: string = "";
	message_type?: StatusMessage;
	children_error_messages: string[] = [];
	static _child?: Element;

	constructor(data?: Element | Object) {
		if (!data) return;

		let ndata = data as Element;

		this.set_data(ndata);
	}

	get msg_r() {
		return this.has_error();
	}
	//?this = data
	set_data(data: Element) {
		this.id = data.id;
		this.class = data.class || "";
		this.name = data.name;
		this.value = data.value;
		this.type = data.type;
		this.label = data.label;
		this.action = data.action;
		this.dismiss = data.dismiss;
		this.require = data.require;
		this.placeholder = data.placeholder;
		this.parameter = data.parameter;
		this.tag = data.tag;
		this.child = data.child || {};
		this.msg = data.msg;
		this.message_type = data.message_type;
		this.children_error_messages = data.children_error_messages || [];
		return this;
	}
	set_name(name: string) {
		this.name = name;
		return this;
	}

	set_id(id: string | number) {
		this.id = String(id);
		return this;
	}

	set_class(cls: string) {
		this.class += " " + cls;
		return this;
	}

	drop_class(cls: string) {
		this.class = "this.class".replace(cls, "");
	}

	set_value(value: any) {
		this.value = value;
		return this;
	}

	set_type(type: InputTypeComponent | InputTypeButton | InputTypeAll) {
		this.type = type;
		return this;
	}

	set_label(label: string) {
		this.label = label;
		return this;
	}

	set_action(action: string) {
		this.action = action;
		return this;
	}

	set_dismiss(dismiss: boolean) {
		this.dismiss = dismiss;
		return this;
	}

	set_require(is_nullable: string) {
		if (is_nullable === "YES") {
			this.require = false;
			return this;
		}

		this.require = true;
		this.label += "  *";
		return this;
	}

	set_placeholder(placeholder: string) {
		this.placeholder = placeholder;
		return this;
	}

	create_child(block_name = "main", data: Element | Object = {}) {
		// this.child = new Element({parent:this});
		let child: Element = new Element(data);

		if (!Object.hasOwn(this.child, block_name)) {
			this.child[block_name] = [];
		}

		this.child[block_name].push(child);

		return child;
	}

	set_parent_name(parent_name: string) {
		this.parent_name = parent_name;
		return this;
	}

	/**
	 * Iterates over data array and executes a function for each element
	 * @param {Array} data - Array of data to iterate over
	 * @param {string} func - Name of the method to call for each data element
	 * @param {string} child_name - Name of the child property to store results
	 * @returns {this} Returns the current instance for method chaining
	 */
	for_data(data: Object[], funcName: keyof this, child_name: string) {
		this.child[child_name] = [];

		data.forEach((d) => {
			let fn = this[funcName];

			if (typeof fn === "function") {
				fn.call(this, d);
			}
		});

		return this.child[child_name];
	}

	/**
	 * Iterates over each child element and executes a function with a given value.
	 * @param {Array} children - The array of child elements to iterate over.
	 * @param {string} fun - The name of the function to call on each child.
	 * @param {*} value - The value to pass as an argument to the function.
	 * @returns {this} Returns the current instance for method chaining.
	 */
	for_children(children: Element[], funcName: keyof this, value: any) {
		children.forEach((child) => {
			let fn = this[funcName];

			if (!(typeof fn === "function")) return;

			fn.call(child, value);
		});
		return this;
	}

	set_parameter(param: string) {
		this.parameter = param;
		return this;
	}

	message(message: string, status: StatusMessage) {
		this.msg = message;
		this.message_type = status;
		return this;
	}

	get child_key() {
		return "main_" + this.name;
	}

	input_error(parent_element: Element) {
		this.message(
			'o campo "' + (this.label || this.id) + '" é obrigatório.',
			StatusMessage.error,
		);
		parent_element._set_message_parent(this.name || "");
		return this;
	}

	error() {
		this.message("Houve um erro inesperado", StatusMessage.error);
		return this;
	}
	/**
	 * @argument parent_element element where contain this
	 * @description remove parent error
	 */
	success(parent_element?: Element) {
		let message = "ok";
		this.message(message, StatusMessage.success);

		if (parent_element && this.name) {
			parent_element._remove_message_parent(this.name);
		}
		return this;
	}

	/**
	 * @description remove child error from parent
	 * @argument child_key - the element name
	 */
	_remove_message_parent(child_key: string) {
		let index = this.children_error_messages.indexOf(child_key);
		if (index < 0) {
			return;
		}
		this.children_error_messages.splice(index, 1);
	}

	/**
	 * @description set child error from parent
	 * @argument child_key - the element name
	 */
	_set_message_parent(child_key: string) {
		this.children_error_messages.push(child_key);
	}

	has_error_child($tolerance = 0) {
		return this.children_error_messages.length > $tolerance;
	}

	has_error() {
		return this.message_type === "error";
	}

	get_element() {
		let parseJson = JSON.stringify(this);
		return Object.assign({}, JSON.parse(parseJson));
	}

	/**
	 * Retrieves a child element from the children collection by block name and optional index.
	 *
	 * @param {string} [block_name="main"] - The name of the child block to retrieve. Defaults to "main".
	 * @param {number|null} [index=null] - Optional index to retrieve a specific child from the block.
	 *                                      If provided, returns the child at that index position.
	 * @param {boolean} [recover=false] - If true, returns the previously cached child element stored in Element._child.
	 *
	 * @returns {Element} - Returns the child element object matching the block_name and optional index.
	 *                          Returns null if the block_name does not exist in children.
	 *                          Returns the cached Element._child if recover is true.
	 *
	 * @example
	 * // Get the main block's children
	 * const mainChild = element.get_child();
	 *
	 * @example
	 * // Get a specific child at index 2 from the "sidebar" block
	 * const sidebarChild = element.get_child("sidebar", 2);
	 *
	 * @example
	 * // Recover the last retrieved child from cache
	 * const cachedChild = element.get_child("main", null, true);
	 */
	get_child(block_name = "main", index: number, recorvere = false) {
		if (recorvere) return Element._child as Element;

		let children = this.child;
		let child: Element | undefined = undefined;

		if (!children.hasOwnProperty(block_name)) {
			Element._child = undefined;

			return new Element().error();
		}

		let children_in_block: Element[] = children[block_name];

		child = children_in_block[index];

		Element._child = child;
		return child;
	}

	/**
	 * Retrieves a child element from the children collection by block name and optional index.
	 *
	 * @param {string} [block_name="main"] - The name of the child block to retrieve. Defaults to "main".
	 * @param {number|null} [index=null] - Optional index to retrieve a specific child from the block.
	 *                                      If provided, returns the child at that index position.
	 * @param {boolean} [recover=false] - If true, returns the previously cached child element stored in Element._child.
	 *
	 * @returns {Object|null} - Returns the child element object matching the block_name and optional index.
	 *                          Returns null if the block_name does not exist in children.
	 *                          Returns the cached Element._child if recover is true.
	 *
	 * @example
	 * // Get the main block's children
	 * const mainChild = element.get_child();
	 *
	 * @example
	 * // Get a specific child at index 2 from the "sidebar" block
	 * const sidebarChild = element.get_child("sidebar", 2);
	 *
	 * @example
	 * // Recover the last retrieved child from cache
	 * const cachedChild = element.get_child("main", null, true);
	 */
	get_all_child(block_name = "main") {
		let children = this.child;

		if (!children.hasOwnProperty(block_name)) {
			return [];
		}

		let child: Element[] = children[block_name];

		return child;
	}
	/**
	 * @argument value_key - the element name
	 * @argument block_name - the block name
	 * @returns Element:class child
	 */
	search_child(
		value_key: string,
		block_name = "main",
		by: keyof Element = "name",
	) {
		if (!this.child.hasOwnProperty(block_name)) {
			// this.element_error("Bloco '" + block_name + "' não existe.");
			Element._child = undefined;

			return null;
		}
		let children = this.child[block_name];

		let child_finded = children.filter((item) => {
			if (item[by] == value_key) {
				return item;
			}
		});

		Element._child = child_finded[0];
		return child_finded[0];
	}

	/**
	 * @param {string} value_key received string, the default is the string 'null'
	 * @param {string} [block_name="main"] received string, the default is 'main'
	 * @param {string} [by="name"] received string, the default is 'name'
	 * @returns {Element|false}
	 */
	hasChild(
		block_name = "main",
		value_key:string = undefined,
		by: keyof Element = "name",
	) {
		if (!this.child.hasOwnProperty(block_name)) {
			// this.element_error("Bloco '" + block_name + "' não existe.");
			return false;
		}

		if (value_key) return true;

		let children = this.child[block_name];
		return children.some((item) => {
			if (item[by] == value_key) {
				return item;
			}
		});
	}

	hasClass(className: string) {
		return this.class.includes(className);
	}
}
