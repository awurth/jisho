import httpClient from "./http-client.js";

export async function getDecks() {
  const { data } = await httpClient.get("/decks");
  return data;
}

export async function getCards(deckId) {
  const { data } = await httpClient.get(`/decks/${deckId}/cards`);
  return data;
}

export async function getTags(deckId) {
  const { data } = await httpClient.get(`/decks/${deckId}/tags`);
  return data;
}

export async function postDeck(deck) {
  const { data } = await httpClient.post(`/decks`, deck);
  return data;
}

export async function postCard(deckId, entryId) {
  const { data } = await httpClient.post(`/decks/${deckId}/cards`, {
    entry: `/dictionary/entries/${entryId}`,
  });
  return data;
}
