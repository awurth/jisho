import React from "react";
import { createBrowserRouter, redirect } from "react-router-dom";
import {getDecks} from './api/deck.js';
import { getMe } from "./api/user.js";
import Error from "./error.jsx";
import Account from "./pages/account.jsx";
import Home from "./pages/home.jsx";
import Login from "./pages/login.jsx";
import NewDeck from './pages/new-deck.jsx';
import QuizForm from "./pages/quiz-form.jsx";
import Quiz from "./pages/quiz.jsx";
import Root from "./pages/root.jsx";
import Search from "./pages/search.jsx";
import {useDeckStore} from './stores/deck.js';
import { useUserStore } from "./stores/user.js";

const mustBeLoggedIn = async () => {
  try {
    const user = await getMe();
    useUserStore.setState({ user });

    if (!useDeckStore.getState().activeDeck) {
      const decks = await getDecks();
      if (decks.length) {
        useDeckStore.setState({ activeDeck: decks[0] });
      }
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
