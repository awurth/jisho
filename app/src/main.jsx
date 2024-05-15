import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import axios from "axios";
import React from "react";
import ReactDOM from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import { router } from "./routes.jsx";
import "./index.scss";
import { useUserStore } from "./stores/user.js";

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

const queryClient = new QueryClient();

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <RouterProvider router={router} />
      {/*<ReactQueryDevtools initialIsOpen={false} />*/}
    </QueryClientProvider>
  </React.StrictMode>,
);
