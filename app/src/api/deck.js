import axios from "axios";

export async function getDecks() {
  const { data } = await axios.get("/api/decks");
  return data;
}

export async function getCards(deckId) {
  const { data } = await axios.get(`/api/decks/${deckId}/cards`);
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

export async function postCard(deckId, entryId) {
  const { data } = await axios.post(`/api/decks/${deckId}/cards`, {
    entry: `/api/dictionary/entries/${entryId}`,
  });
  return data;
}
