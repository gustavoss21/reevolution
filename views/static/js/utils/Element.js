import { handleError } from "vue";

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
  msg = "";
  message_type = "";
  children_error_messages = [];

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
    if(is_nullable === 'YES'){
      this.require = false;
      return this;
    }

    this.require = true;
    // let has_ = this.label.search("*");
    this.label +=  "  *";
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

  for_data(fun, child_name, data) {
    this.child[child_name] = [];
    data.forEach((d) => {
      this[fun](child_name, d);
    });
    return this;
  }

  /**
   * @argument fun - function name to call on children
   * @argument child_name - the block name
   * @argument data - data to pass to function
   */
  for_children(fun, child_name, data) {
    let children = this.get_child(child_name);
    // this.child[child_name] = [];
    if (!children) throw new handleError("child not found in for_children");

    children.forEach((child) => {
      child[fun](data);
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
  get_child(block_name = "main", index = null) {
    let child = this.child;

    if (Object.hasOwn(child, block_name)) {
      child = child[block_name];
    } else {
      return null;
    }
    if (typeof index == "number") {
      return child[index];
    }

    return child;
  }
  /**
   * @argument value_key - the element name
   * @argument block_name - the block name
   * @returns Element:class child
   */
  search_child(value_key, block_name = "main",by='name') {
    if (!Object.hasOwn(this.child, block_name)) {
      // this.element_error("Bloco '" + block_name + "' não existe.");
      return null;
    }
    let children = this.child[block_name];

    let child_finded = children.filter((item) => {
      if (item[by] == value_key) {
        return item;
      }
    });

    return child_finded[0];
  }
}

// let e = new Element()
//   .set_name('teste')
//   .set_label()

// let a = e.set_child()
//   .set_name('chil')
// let c = e.set_child().set_name("chil").set_value("value1");
// let b = e.set_child().set_name("cdren").set_value("value2");

// let r = e.search_child('chil');

// console.log(c.msg_r);
// console.log(b.msg_r);