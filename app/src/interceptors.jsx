import httpClient from "./api/http-client.js";
import { router } from "./routes.jsx";
import { useUserStore } from "./stores/user.js";

httpClient.interceptors.request.use(
  function (config) {
    const token = localStorage.getItem("token");

    if (token && !config.headers.Authorization) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  function (error) {
    return Promise.reject(error);
  },
);

httpClient.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response?.status === 401) {
      useUserStore.setState({ user: null });
      router.navigate("/login");
    }

    return Promise.reject(error);
  },
);
