"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var ElementTest = /** @class */ (function () {
    function ElementTest() {
    }
    ElementTest.value = "teste";
    return ElementTest;
}());
var ins = new ElementTest();
console.log(ins.value.indexOf("a"));
