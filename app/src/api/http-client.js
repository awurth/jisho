import axios from "axios";
import { router } from "../routes.jsx";
import { useUserStore } from "../stores/user.js";

const httpClient = axios.create({
  baseURL: "https://api.jisho.localhost",
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
      router.navigate("/jisho/logout");
    }

    return Promise.reject(error);
  },
);

export default httpClient;
