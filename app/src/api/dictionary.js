import axios from "axios";

export async function search(query, config = {}) {
  const { data } = await axios.get(`/api/search/${query}}`, config);
  return data;
}
