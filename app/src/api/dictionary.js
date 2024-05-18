import axios from "axios";

export async function search(query, config = {}) {
  const { data } = await axios.get(`/api/search/${query}`, config);
  return data;
}

export async function getEntry(id) {
  const { data } = await axios.get(`/api/dictionary/entries/${id}`);
  return data;
}
