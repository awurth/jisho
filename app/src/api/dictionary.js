import httpClient from "./http-client.js";

export async function search(query, config = {}) {
  const { data } = await httpClient.get(`/search/${query}`, config);
  return data;
}

export async function getEntry(id) {
  const { data } = await httpClient.get(`/dictionary/entries/${id}`);
  return data;
}
