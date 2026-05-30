import {ResponseDataType} from "@/utils/HomeType";

export class ApiClient {
	baseURL: string;

	constructor(roteHref: string='') {
		this.baseURL = roteHref.replace(/\/$/,'');
	}

	async request(
		endpoint: string,
		method = "GET",
		data: Object | any,
		headers = {},
	): Promise<ResponseDataType> {
		const options = {
			method,
			body: null as string | null,
			headers: {"Content-Type": "application/json", ...headers},
		};

		if (data) {
			options.body = JSON.stringify(data);
		}

		try {
			const response = await fetch(`${this.baseURL}${endpoint}`, options);
			if (!response.ok) {
				throw new Error(`Erro ${response.status}: ${response.statusText}`);
			}
			return await response.json();
		} catch (error) {
			console.error("Erro na requisição:", error);
			throw await error;
		}
	}

	get(endpoint: string, headers = {}): Promise<ResponseDataType> {
		return this.request(endpoint, "GET", null, headers);
	}

	post(endpoint: string, data: Object, headers = {}): Promise<ResponseDataType> {
		return this.request(endpoint, "POST", data, headers);
	}

	put(endpoint: string, data: Object, headers = {}): Promise<ResponseDataType> {
		return this.request(endpoint, "PUT", data, headers);
	}

	delete(endpoint: string, headers = {}): Promise<ResponseDataType> {
		return this.request(endpoint, "DELETE", null, headers);
	}
}
