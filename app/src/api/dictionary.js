import axios from "axios";

export async function getDictionaries() {
  const { data } = await axios.get("/api/dictionaries");
  return data;
}

export async function getEntries(dictionaryId) {
  const { data } = await axios.get(`/api/dictionaries/${dictionaryId}/entries`);
  return data;
}

export async function getTags(dictionaryId) {
  const { data } = await axios.get(`/api/dictionaries/${dictionaryId}/tags`);
  return data;
}

export async function postEntry(dictionaryId, entry) {
  const { data } = await axios.post(
    `/api/dictionaries/${dictionaryId}/entries`,
    entry,
  );
  return data;
}
