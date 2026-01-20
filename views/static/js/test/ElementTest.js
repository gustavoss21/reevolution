import {Element} from "../utils/Element.js";
class ElementTest{
    hasChildTest(){
        let element = new Element({name:'testePai'});
        element.set_child()
               .set_name('child');
        
        element.hasChild()
        console.log(element.hasChild());

    }

    getChildTest(){
        let element = new Element({ name: "testePai" });
        element
        .set_child()
        .set_name("child")
        .set_child()
        .set_name("child12");

        let ele = element.get_child("main", 0);
        console.log(ele.get_child("", 0, true).get_child("main", 0, ));
    }
}
new ElementTest().getChildTest();

