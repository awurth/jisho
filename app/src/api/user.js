import httpClient from "./http-client.js";

export async function connectWithGoogle(accessToken) {
  const { data } = await httpClient.post("/connect/google", {
    token: accessToken,
  });

  return data;
}
