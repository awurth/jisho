import axios from "axios";

export async function getMe() {
  const { data } = await axios.get("/api/me");
  return data;
}

export async function logout() {
  const { data } = await axios.get("/api/logout");
  return data;
}
