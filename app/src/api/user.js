import axios from "axios";

export async function logout() {
  const { data } = await axios.get("/api/logout");
  return data;
}
