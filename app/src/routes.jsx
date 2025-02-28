import React from "react";
import { createBrowserRouter, redirect } from "react-router";
import { getDecks } from "./api/deck.js";
import Error from "./error.jsx";
import Account from "./pages/account.jsx";
import Entry from "./pages/entry.jsx";
import Home from "./pages/home.jsx";
import Login from "./pages/login.jsx";
import NewDeck from "./pages/new-deck.jsx";
import NewQuiz from "./pages/new-quiz.jsx";
import Quiz from "./pages/quiz.jsx";
import Root from "./pages/root.jsx";
import Search from "./pages/search.jsx";
import { useDeckStore } from "./stores/deck.js";
import { useUserStore } from "./stores/user.js";

const mustBeLoggedIn = async () => {
  const token = localStorage.getItem("token");

  if (!token) {
    return redirect("/login");
  }

  if (!useUserStore.getState().user) {
    return redirect("/login");
  }

  if (!useDeckStore.getState().activeDeck) {
    const decks = await getDecks();
    if (decks.length) {
      useDeckStore.setState({ activeDeck: decks[0] });
    }
  }

  return null;
};

export const router = createBrowserRouter([
  {
    path: "/",
    element: <Root />,
    errorElement: <Error />,
    loader: mustBeLoggedIn,
    children: [
      {
        index: true,
        element: <Home />,
      },
      {
        path: "account",
        element: <Account />,
      },
      {
        path: "new-deck",
        element: <NewDeck />,
      },
      {
        path: "search",
        element: <Search />,
      },
      {
        path: "new-quiz",
        element: <NewQuiz />,
      },
      {
        path: "quiz",
        element: <Quiz />,
      },
      {
        path: "entry/:id",
        element: <Entry />,
      },
    ],
  },
  {
    path: "/login",
    element: <Login />,
  },
]);
