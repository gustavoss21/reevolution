import { s } from "vite/dist/node/chunks/moduleRunnerTransport";
import {DataType} from "./dataType.ts";

type DataAll = {data: FormCore[]; labelS: Record<string, string>};
type FormCore = {
	COLUMN_NAME              : string;
	DATA_TYPE                : keyof typeof DataType;
	CHARACTER_MAXIMUM_LENGTH?: number;
	COLUMN_DEFAULT          ?: any;
	IS_NULLABLE              : string;
	label                    : string;
	tag?: boolean;
};

export {DataAll, FormCore};
