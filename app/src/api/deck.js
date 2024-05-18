import axios from "axios";

export async function getDecks() {
  const { data } = await axios.get("/api/decks");
  return data;
}

export async function getEntries(deckId) {
  const { data } = await axios.get(`/api/decks/${deckId}/entries`);
  return data;
}

export async function getTags(deckId) {
  const { data } = await axios.get(`/api/decks/${deckId}/tags`);
  return data;
}

export async function postDeck(deck) {
  const { data } = await axios.post(`/api/decks`, deck);
  return data;
}

export async function postDeckEntry(deckId, entryId) {
  const { data } = await axios.post(`/api/decks/${deckId}/entries`, {
    entry: `/api/dictionary/entries/${entryId}`,
  });
  return data;
}
