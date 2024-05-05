import React from "react";
import { createBrowserRouter, redirect } from "react-router-dom";
import { getDictionaries } from "./api/dictionary.js";
import { getMe } from "./api/user.js";
import Error from "./error.jsx";
import Account from "./pages/account.jsx";
import Dictionary from "./pages/dictionary.jsx";
import Login from "./pages/login.jsx";
import QuizForm from "./pages/quiz-form.jsx";
import Quiz from "./pages/quiz.jsx";
import Root from "./pages/root.jsx";
import Search from "./pages/search.jsx";
import { useDictionaryStore } from "./stores/dictionary.js";
import { useUserStore } from "./stores/user.js";

const mustBeLoggedIn = async () => {
  try {
    const user = await getMe();
    useUserStore.setState({ user });

    if (!useDictionaryStore.getState().activeDictionary) {
      const dictionaries = await getDictionaries();
      useDictionaryStore.setState({ activeDictionary: dictionaries[0] });
    }

    return null;
  } catch (error) {
    useUserStore.setState({ user: null });
    return redirect("/login");
  }
};

export const router = createBrowserRouter([
  {
    path: "/",
    loader: mustBeLoggedIn,
    element: <Root />,
    errorElement: <Error />,
    children: [
      {
        index: true,
        element: <Dictionary />,
      },
      {
        path: "account",
        element: <Account />,
      },
      {
        path: "search",
        element: <Search />,
      },
      {
        path: "new-quiz",
        element: <QuizForm />,
      },
      {
        path: "quiz",
        element: <Quiz />,
      },
    ],
  },
  {
    path: "/login",
    element: <Login />,
  },
]);
