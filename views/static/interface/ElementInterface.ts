export enum StatusMessage {
	error = 'error',
	success = 'success'

}

export interface ElementInterface {
	name?: string;
	type?: string;
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

export interface ElementMethodsInterface {
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
	has_error()                                                : boolean;
}