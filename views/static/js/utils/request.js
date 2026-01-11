export class ApiClient {
  constructor(baseURL) {
    this.baseURL = baseURL;
  }

  async request(endpoint, method = "GET", data = null, headers = {}) {
    const options = {
      method,
      headers: {
        "Content-Type": "application/json",
        ...headers,
      },
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

  get(endpoint, headers = {}) {
    return this.request(endpoint, "GET", null, headers);
  }

  post(endpoint, data, headers = {}) {
    return this.request(endpoint, "POST", data, headers);
  }

  put(endpoint, data, headers = {}) {
    return this.request(endpoint, "PUT", data, headers);
  }

  delete(endpoint, headers = {}) {
    return this.request(endpoint, "DELETE", null, headers);
  }
}
