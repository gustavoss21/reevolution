import { Element } from "./Element.js";
import dataType from "./dataType.js";
import translation from "./transletion.js";
export class ManageStap {
  stap_index = null;
  element = null;
  element_parent = new Element({ name: "parent" });
  stap = null;
  staps = [];
  stap_nav = new Element({ name: "stap" });
  stap_nav_index = 0;
  data_children = {};
  element_rest = [];
  id = '';

  getStap() {
    let element = this.staps[this.stap_index];

    if (!element) {
      console.error("fun:getStap -> Elemento nao definido");
      return {};
    }

    this.element = element;

    return element;
  }

  setElement() {
    this.element = this.staps[this.stap_index];
  }

  next_stap() {
    let index = this.stap_index + 1;
    this.navigate_to_stap(index,false);
  }

  jump_stap(index) {
    this.navigate_to_stap(index,false);
  }

  setNav(newIndex, oldIndex, start=true) {
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
      .set_child()
      .set_name("stap")
      .set_class("nav-item-modal")
      .set_id(index)
      .set_value(this.stap_nav_index)
      .input_error(this.stap_nav);
  }

  generateElement(data, id) {
    this.element_parent = new Element({ name: "parent" });
    this.staps = [];
    this.stap_nav = new Element({ name: "stap" });
    this.stap_nav_index = 0;
    this.data_children = {};
    this.element_rest = [];
    let all_data_form = this.filterRawData(data);
    let element = null;
    let form_labels = {};
    this.id = id;

    if (!all_data_form) return;

    all_data_form.forEach((key) => {
      let form_data = data[key]["data"];
      form_labels = data[key]["labelS"];
      let element = this.createElement(key);

      if (this.element_rest.length > 0) {
        this.setElementData(this.element_rest, form_labels, element);
        this.element_rest = [];
      }
      element = this.setElementData(form_data, form_labels, element);
    });

    if (this.element_rest.length > 0) {
      element = element ?? this.createElement(id);
      
      this.setElementData(this.element_rest, form_labels, element);
      this.element_rest = [];
    }

    this.navigate_to_stap();
  }

  filterRawData(data=[]) {
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
  setElementData(form_data, form_labels, element, pagination = true) {
    let key_label_list = Object.keys(form_labels);
    let inputs_length = form_data.length;
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
      let column_type = dataType[el.DATA_TYPE];
      let pathern_radio = new RegExp(`${column_name}.+`);
      let column_radio_filted = columns_radio.filter((v) =>
        pathern_radio.test(v)
      );

      if (!form_labels[column_name]) continue;

      let element_c = element
        .set_child()
        .set_label(form_labels[column_name])
        .set_name(column_name)
        .set_id(column_name)
        .set_type(column_type)
        .set_value(el.COLUMN_DEFAULT)
        .set_parent_name(element.name)
        .set_require(el.IS_NULLABLE);

      //radio
      if (column_radio_filted.length > 0) {
        this.elementRadio(element_c, column_radio_filted, form_labels);
      }
      //request
      else if (column_type === "number" && pathern_id.exec(column_name)) {
        this.elementSearch(element_c, form_labels, column_name);
      }

      let required_next_step = (pagination && Number.isInteger((index + 1) / 4) && inputs_length - 4 >= 3)
      
      if (required_next_step) {
        if (inputs_length - 1 <= index + 3) {
          this.element_rest = form_data.slice(index);
          return element;
        }
        element = this.createElement(element.name);
      }
    }

    return element;
  }
  createElement(el_name, is_active = false, button_submit = null, data = {}) {
    let element = this.element_parent
      .set_child("main", data)
      .set_id(this.id)
      .set_action("create");

    this.staps.push(element);
    this.setButton(element, button_submit);
    this.createStpas(is_active);
    element.set_name(el_name);
    return element;
  }
  setButton(element, submit = false) {
    let button = element.get_child("btn_main", 1);
    if (!button) {
      let btn_closed = element
        .set_child("btn_main")
        .set_label("Fechar")
        .set_type("button")
        .set_class("btn btn-secondary")
        .set_action("close");

      button = element
        .set_child("btn_main")
        .set_label("Próximo")
        .set_type("submit")
        .set_name("submit")
        .set_class("btn btn-primary")
        .set_action("next")
        .set_dismiss(false);
    }

    if (submit) {
      element.for_children("set_dismiss", "btn_main", true);
      button.set_label("Criar").set_action("criar");
    }
  }

  navigate_to_stap(index = 0, start=true) {
    if (index < 0 || index >= this.staps.length) {
      index = 0;
    }
    if (this.stap_index == null) {
      this.stap_index = index;
    }
    this.setNav(index, this.stap_index, start);
    this.stap_index = index;
    this.setElement();

    if (!this.stap_nav.has_error_child(1)) {
      this.setButton(this.element, true);
    }
  }

  re_index_description(data, index) {
    if (!data[index] || !(data[index].DATA_TYPE === "text")) return data;
    let index_ele = index + 1;
    let division_result = index_ele / 4;
    let lenght_data = data.length;

    if (lenght_data < 4 || lenght_data - 4 <= 3) {
      return this.re_index_data(data, lenght_data - 1, index);
    }

    if (Number.isInteger(division_result)) return data;

    let division_int = Number.parseInt(division_result);
    let division_rest = index_ele % 4;
    let division_rest_for_int = 4 - division_rest;
    let new_index =
      division_int * 4 + division_rest + division_rest_for_int - 1;

    return this.re_index_data(data, new_index, index);
  }

  re_index_data(data, new_i, old_id) {
    if (!data[new_i]) return data;
    if (new_i == old_id) return data;

    let value = data[old_id];
    data[old_id] = data[new_i];
    data[new_i] = value;

    return data;
  }

  re_index_title(data) {
    let index = data.findIndex((column) => column.COLUMN_NAME === "name");
    if (index == -1) return data;

    return this.re_index_data(data, 0, index);
  }

  is_invalid() {
    let element_data = this.element;
    let children = element_data.get_child();
    let is_invalid = false;
    children.forEach((child) => {
      let value = child.value;
      let name = child.name;

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
    let children = this.element_parent.get_child();

    children.forEach((parent) => {
      let search_parent = this.element_parent.search_child(
        parent.name,
        "form_data"
      );

      if (!search_parent) {
        search_parent = this.element_parent
          .set_child("form_data")
          .set_name(parent.name);
      }
      
      let children = parent.get_child();

      children.forEach((element) => {
        this.set_input_data_form(search_parent, element);
      });
    });
  }

  set_input_data_form(parent, element) {

    let child = parent.set_child("main", element);

    if (element.type == "request") {
      let new_child = element.get_child("id_hidden", 0);
      child.set_value(new_child.value);
      child.set_id(new_child.id);
    }

    return child;
  }

  elementRadio(element_c, columns_radio, form_labels) {
    element_c.set_type("radio");

    for (let label_key of columns_radio) {
      element_c
        .set_child()
        .set_label(form_labels[label_key])
        .set_value(label_key)
        .set_id(label_key)
        .set_name(label_key)
        .set_parent_name(element_c.parent_name);
    }
  }
  elementSearch(element_c, form_labels, column_name) {
    let name_base = column_name.replace(/(.+)_id/, "$1");
    let data_child = this.data_children[column_name];

    if (!data_child) return;
    element_c
      .set_name(name_base + "_name")
      .set_id(name_base + "_name")
      .set_class("card collapse")
      .set_action("requestL")
      .set_placeholder("escreva e selecione o ...")
      .set_type("request")

      .set_child("id_hidden")
      .set_name(column_name)
      .set_id(column_name)
      .set_parameter("hidden")
      .set_parent_name(column_name);

    let title = "criar " + form_labels[column_name];
    let form_event_create = element_c
      .set_child()
      .set_label(title)
      .set_action("create")
      // .set_type("collapse")
      .set_type("request")

    let el = this.setElementData(
      data_child["data"],
      data_child["labelS"],
      form_event_create,
      false
    );

    this.setButton(el, true);
  }
}
