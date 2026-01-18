import { handleError, Static } from "vue";

export class Element {
  id;
  class;
  name;
  value;
  type;
  label;
  action;
  dismiss;
  require;
  placeholder;
  parameter;
  parent_name;
  tag = "";
  child = {};
  data_child = {};
  msg = "";
  message_type = "";
  children_error_messages = [];
  search_full = null;
  static _child = {};

  constructor(data = {}) {
    this.set_data(data);
  }
  get msg_r() {
    return this.has_error();
  }
  set_data(data) {
    this.id = data.id;
    this.class = data.class;
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
  set_name(name) {
    this.name = name;
    return this;
  }

  set_id(id) {
    this.id = id;
    return this;
  }

  set_class(cls) {
    this.class = cls;
    return this;
  }

  set_value(value) {
    this.value = value;
    return this;
  }

  set_type(type) {
    this.type = type;
    return this;
  }

  set_label(label) {
    this.label = label;
    return this;
  }

  set_action(action) {
    this.action = action;
    return this;
  }

  set_dismiss(dismiss) {
    this.dismiss = dismiss;
    return this;
  }

  set_require(is_nullable) {
    if (is_nullable === "YES") {
      this.require = false;
      return this;
    }

    this.require = true;
    // let has_ = this.label.search("*");
    this.label += "  *";
    return this;
  }

  set_placeholder(placeholder) {
    this.placeholder = placeholder;
    return this;
  }

  set_child(block_name = "main", data = {}) {
    // this.child = new Element({parent:this});

    if (!Object.hasOwn(this.child, block_name)) {
      this.child[block_name] = [];
    }

    let index = this.child[block_name].push(new Element(data)) - 1;
    let child = this.child[block_name][index];
    return child;
  }

  set_parent_name(parent_name) {
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
  for_data(data,func, child_name  ) {
    this.child[child_name] = [];
    data.forEach((d) => {
      this[func](child_name, d);
    });
    
    return this.child[child_name]
  }

  
  /**
   * Iterates over each child element and executes a function with a given value.
   * @param {Array} children - The array of child elements to iterate over.
   * @param {string} fun - The name of the function to call on each child.
   * @param {*} value - The value to pass as an argument to the function.
   * @returns {this} Returns the current instance for method chaining.
   */
  for_children(children, fun, value) {

    children.forEach((child) => {
      child[fun](value);
    });
    return this;
  }

  set_parameter(param) {
    this.parameter = param;
    return this;
  }

  message(message, status) {
    this.msg = message;
    this.message_type = status;
    return this;
  }

  get child_key() {
    return "main_" + this.name;
  }

  input_error(parent_element) {
    this.message(
      'o campo "' + (this.label || this.id) + '" é obrigatório.',
      "error"
    );
    parent_element._set_message_parent(this.name);
    return this;
  }

  error() {
    this.message("Houve um erro inesperado", "error");
    return this;
  }
  /**
   * @argument parent_element element where contain this
   * @description remove parent error
   */
  success(parent_element = null) {
    let message = "ok";
    this.message(message, "success");
    if (parent_element) {
      parent_element._remove_message_parent(this.name);
    }
    return this;
  }

  /**
   * @description remove child error from parent
   * @argument child_key - the element name
   */
  _remove_message_parent(child_key) {
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
  _set_message_parent(child_key) {
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
  get_child(block_name = "main", index = null, recorvere = false) {
    if (recorvere) return Element._child;

    let children = this.child;

    if (!Object.hasOwn(children, block_name)) {
      Element._child = {};

      return null;
    }

    let child = children[block_name];

    if (typeof index == "number") {
      child = child[index];
    }

    Element._child = child;
    return child;
  }
  /**
   * @argument value_key - the element name
   * @argument block_name - the block name
   * @returns Element:class child
   */
  search_child(value_key, block_name = "main", by = "name") {
    if (!Object.hasOwn(this.child, block_name)) {
      // this.element_error("Bloco '" + block_name + "' não existe.");
      Element._child = {};

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
  hasChild(value_key = "null", block_name = "main", by = "name") {
    if (!Object.hasOwn(this.child, block_name)) {
      // this.element_error("Bloco '" + block_name + "' não existe.");
      return false;
    }

    if (value_key === "null") return true;

    let children = this.child[block_name];
    return children.some((item) => {
      if (item[by] == value_key) {
        return item;
      }
    });
  }
}

