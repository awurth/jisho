import axios from "axios";

export async function connectWithGoogle(accessToken) {
  const { data } = await axios.post(
    "/api/connect/google",
    {},
    {
      headers: {
        Authorization: `Bearer ${accessToken}`,
      },
    },
  );

  return data;
}
