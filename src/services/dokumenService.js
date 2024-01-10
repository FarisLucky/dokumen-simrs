import { http } from "../config/http";

const get = async (url) => {
  const response = await http.get(url);

  return response.data;
};

const find = async (url) => {
  const response = await http.get(url);

  return response.data;
};

const store = async (url, data, options) => {
  const response = await http.post(url, data, options);

  return response.data;
};

const put = async (url, data, options) => {
  const response = await http.put(url, data, options);

  return response.data;
};

const destroy = async (url, data, options) => {
  const response = await http.delete(url, data, options);

  return response.data;
};

const history = async (url, data, options) => {
  const response = await http.get(url, data, options);

  return response.data;
};

export default {
  get,
  find,
  store,
  put,
  destroy,
  history,
};
