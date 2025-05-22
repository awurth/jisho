import axios from "axios";
import { router } from "../routes.jsx";

const httpClient = axios.create({
  baseURL: import.meta.env.DEV
    ? "https://api.jiish.localhost"
    : "https://api.jiish.io",
});

httpClient.defaults.headers.patch["Content-Type"] =
  "application/merge-patch+json";

httpClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");

    if (token && !config.headers.Authorization) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => Promise.reject(error),
);

httpClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      router.navigate("/logout");
    }

    return Promise.reject(error);
  },
);

export default httpClient;
