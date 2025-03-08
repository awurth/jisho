import axios from "axios";

const httpClient = axios.create({
  baseURL: "https://api.jisho.localhost",
});

httpClient.defaults.headers.patch["Content-Type"] =
  "application/merge-patch+json";

export default httpClient;
