"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Element = void 0;
var ElementInterface_ts_1 = require("@/interface/ElementInterface.ts");
var Element = /** @class */ (function () {
    function Element(data) {
        this.class = "";
        this.tag = "";
        this.child = {};
        this.data_child = {};
        this.msg = "";
        this.children_error_messages = [];
        if (!data)
            return;
        var ndata = data;
        this.set_data(ndata);
    }
    Object.defineProperty(Element.prototype, "msg_r", {
        get: function () {
            return this.has_error();
        },
        enumerable: false,
        configurable: true
    });
    //?this = data
    Element.prototype.set_data = function (data) {
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
    };
    Element.prototype.set_name = function (name) {
        this.name = name;
        return this;
    };
    Element.prototype.set_id = function (id) {
        this.id = String(id);
        return this;
    };
    Element.prototype.set_class = function (cls) {
        this.class += " " + cls;
        return this;
    };
    Element.prototype.drop_class = function (cls) {
        this.class = "this.class".replace(cls, "");
    };
    Element.prototype.set_value = function (value) {
        this.value = value;
        return this;
    };
    Element.prototype.set_type = function (type) {
        this.type = type;
        return this;
    };
    Element.prototype.set_label = function (label) {
        this.label = label;
        return this;
    };
    Element.prototype.set_action = function (action) {
        this.action = action;
        return this;
    };
    Element.prototype.set_dismiss = function (dismiss) {
        this.dismiss = dismiss;
        return this;
    };
    Element.prototype.set_require = function (is_nullable) {
        if (is_nullable === "YES") {
            this.require = false;
            return this;
        }
        this.require = true;
        this.label += "  *";
        return this;
    };
    Element.prototype.set_placeholder = function (placeholder) {
        this.placeholder = placeholder;
        return this;
    };
    Element.prototype.create_child = function (block_name, data) {
        if (block_name === void 0) { block_name = "main"; }
        if (data === void 0) { data = {}; }
        // this.child = new Element({parent:this});
        var child = new Element(data);
        if (!Object.hasOwn(this.child, block_name)) {
            this.child[block_name] = [];
        }
        this.child[block_name].push(child);
        return child;
    };
    Element.prototype.set_parent_name = function (parent_name) {
        this.parent_name = parent_name;
        return this;
    };
    /**
     * Iterates over data array and executes a function for each element
     * @param {Array} data - Array of data to iterate over
     * @param {string} func - Name of the method to call for each data element
     * @param {string} child_name - Name of the child property to store results
     * @returns {this} Returns the current instance for method chaining
     */
    Element.prototype.for_data = function (data, funcName, child_name) {
        var _this = this;
        this.child[child_name] = [];
        data.forEach(function (d) {
            var fn = _this[funcName];
            if (typeof fn === "function") {
                fn.call(_this, d);
            }
        });
        return this.child[child_name];
    };
    /**
     * Iterates over each child element and executes a function with a given value.
     * @param {Array} children - The array of child elements to iterate over.
     * @param {string} fun - The name of the function to call on each child.
     * @param {*} value - The value to pass as an argument to the function.
     * @returns {this} Returns the current instance for method chaining.
     */
    Element.prototype.for_children = function (children, funcName, value) {
        var _this = this;
        children.forEach(function (child) {
            var fn = _this[funcName];
            if (!(typeof fn === "function"))
                return;
            fn.call(child, value);
        });
        return this;
    };
    Element.prototype.set_parameter = function (param) {
        this.parameter = param;
        return this;
    };
    Element.prototype.message = function (message, status) {
        this.msg = message;
        this.message_type = status;
        return this;
    };
    Object.defineProperty(Element.prototype, "child_key", {
        get: function () {
            return "main_" + this.name;
        },
        enumerable: false,
        configurable: true
    });
    Element.prototype.input_error = function (parent_element) {
        this.message('o campo "' + (this.label || this.id) + '" é obrigatório.', ElementInterface_ts_1.StatusMessage.error);
        parent_element._set_message_parent(this.name || '');
        return this;
    };
    Element.prototype.error = function () {
        this.message("Houve um erro inesperado", ElementInterface_ts_1.StatusMessage.error);
        return this;
    };
    /**
     * @argument parent_element element where contain this
     * @description remove parent error
     */
    Element.prototype.success = function (parent_element) {
        var message = "ok";
        this.message(message, ElementInterface_ts_1.StatusMessage.success);
        if (parent_element && this.name) {
            parent_element._remove_message_parent(this.name);
        }
        return this;
    };
    /**
     * @description remove child error from parent
     * @argument child_key - the element name
     */
    Element.prototype._remove_message_parent = function (child_key) {
        var index = this.children_error_messages.indexOf(child_key);
        if (index < 0) {
            return;
        }
        this.children_error_messages.splice(index, 1);
    };
    /**
     * @description set child error from parent
     * @argument child_key - the element name
     */
    Element.prototype._set_message_parent = function (child_key) {
        this.children_error_messages.push(child_key);
    };
    Element.prototype.has_error_child = function ($tolerance) {
        if ($tolerance === void 0) { $tolerance = 0; }
        return this.children_error_messages.length > $tolerance;
    };
    Element.prototype.has_error = function () {
        return this.message_type === "error";
    };
    Element.prototype.get_element = function () {
        var parseJson = JSON.stringify(this);
        return Object.assign({}, JSON.parse(parseJson));
    };
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
    Element.prototype.get_child = function (block_name, index, recorvere) {
        if (block_name === void 0) { block_name = "main"; }
        if (recorvere === void 0) { recorvere = false; }
        if (recorvere)
            return Element._child;
        var children = this.child;
        var child = undefined;
        if (!children.hasOwnProperty(block_name)) {
            Element._child = undefined;
            return (new Element()).error();
        }
        var children_in_block = children[block_name];
        child = children_in_block[index];
        Element._child = child;
        return child;
    };
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
    Element.prototype.get_all_child = function (block_name) {
        if (block_name === void 0) { block_name = "main"; }
        var children = this.child;
        if (!children.hasOwnProperty(block_name)) {
            return [];
        }
        var child = children[block_name];
        return child;
    };
    /**
     * @argument value_key - the element name
     * @argument block_name - the block name
     * @returns Element:class child
     */
    Element.prototype.search_child = function (value_key, block_name, by) {
        if (block_name === void 0) { block_name = "main"; }
        if (by === void 0) { by = "name"; }
        if (!this.child.hasOwnProperty(block_name)) {
            // this.element_error("Bloco '" + block_name + "' não existe.");
            Element._child = undefined;
            return null;
        }
        var children = this.child[block_name];
        var child_finded = children.filter(function (item) {
            if (item[by] == value_key) {
                return item;
            }
        });
        Element._child = child_finded[0];
        return child_finded[0];
    };
    /**
     * @param {string} value_key received string, the default is the string 'null'
     * @param {string} [block_name="main"] received string, the default is 'main'
     * @param {string} [by="name"] received string, the default is 'name'
     * @returns {Element|false}
     */
    Element.prototype.hasChild = function (block_name, value_key, by) {
        if (block_name === void 0) { block_name = "main"; }
        if (value_key === void 0) { value_key = "null"; }
        if (by === void 0) { by = "name"; }
        if (!this.child.hasOwnProperty(block_name)) {
            // this.element_error("Bloco '" + block_name + "' não existe.");
            return false;
        }
        if (value_key === "null")
            return true;
        var children = this.child[block_name];
        return children.some(function (item) {
            if (item[by] == value_key) {
                return item;
            }
        });
    };
    Element.prototype.hasClass = function (className) {
        return this.class.includes(className);
    };
    return Element;
}());
exports.Element = Element;
