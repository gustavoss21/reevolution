import InputSearch from "./InputSearch.vue";
import InputRadio from "./InputRadio.vue";
import InputTextarea from "./InputTextarea.vue";
import InputGeneric from "./InputGeneric.vue";

enum StatusMessage {
	error = "error",
	success = "success",
}

enum InputTypeAll{
	date = 'date',
	text = 'text',
	number = 'number',
	radio = 'radio',
	request = 'request',
	textarea = 'textarea',
	reset = 'reset',
	submit = 'submit',
	button = 'button'
}

enum InputTypeButton {
	reset =  'reset',
	submit = 'submit',
	button = 'button',
};

enum InputTypeComponent {
	date = 'date',
	text = 'text',
	number = 'number',
	radio = 'radio',
	// request = 'request',
	textarea = 'textarea'
};

enum InputTypesEnum {
	"date",
	"text",
	"number",
	"radio",
	"request",
	"textarea",
};

interface ElementInterface {
	name?: string;
	type: InputTypeComponent | InputTypeButton | InputTypeAll;
	placeholder?: string;
	parameter?: string;
	id?: string;
	class: string;
	value: any;
	label?: string;
	action?: string;
	dismiss?: boolean;
	require?: boolean;
	parent_name?: string;
	tag: string;
	child: Record<string, ElementInterface[]>;
	data_child: any;
	msg: string;
	message_type?: StatusMessage;
	children_error_messages: string[];
}

interface ElementMethodsInterface {
	set_data(data: ElementInterface): ElementInterface;
	set_name(name: string): ElementInterface;
	set_id(id: number): ElementInterface;
	for_data(
		data: Array<ElementInterface>,
		func: string,
		child_name: string,
	): ElementInterface;
	for_children(
		children: ElementInterface[],
		fun: string,
		value: any,
	): ElementInterface;
	add_child(child_name: string, child_data: ElementInterface): ElementInterface;
	has_error(): boolean;
}


export {
	ElementMethodsInterface,
	ElementInterface,
	StatusMessage,
	InputTypeComponent,
	InputTypesEnum,
	InputTypeButton,
	InputTypeAll,
};