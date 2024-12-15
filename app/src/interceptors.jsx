import axios from "axios";
import { useUserStore } from "./stores/user.js";

axios.interceptors.request.use(
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

axios.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response?.status === 401) {
      useUserStore.setState({ user: null });
    }

    return Promise.reject(error);
  },
);
