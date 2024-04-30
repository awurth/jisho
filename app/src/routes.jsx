import axios from 'axios';
import React from 'react';
import {createBrowserRouter, redirect} from 'react-router-dom';
import Error from './error.jsx';
import Account from './pages/account.jsx';
import Dictionaries from './pages/dictionaries.jsx';
import Dictionary from './pages/dictionary.jsx';
import Login from './pages/login.jsx';
import QuizForm from './pages/quiz-form.jsx';
import Quiz from './pages/quiz.jsx';
import Root from './pages/root.jsx';
import {useDictionaryStore} from './stores/dictionary.js';
import {useUserStore} from './stores/user.js';

const mustBeLoggedIn = async () => {
  try {
    const user = await axios.get('/api/me');
    useUserStore.setState({user: user.data});
    return null;
  } catch (error) {
    useUserStore.setState({user: null});
    return redirect('/login');
  }
};

const mustHaveActiveDictionary = () => {
  if (!useDictionaryStore.getState().activeDictionary) {
    return redirect('/dictionaries');
  }

  return null;
};

export const router = createBrowserRouter([
  {
    path: '/',
    loader: mustBeLoggedIn,
    element: <Root/>,
    errorElement: <Error/>,
    children: [
      {
        index: true,
        loader: mustHaveActiveDictionary,
        element: <Dictionary/>,
      },
      {
        path: 'account',
        element: <Account/>,
      },
      {
        path: 'dictionaries',
        element: <Dictionaries/>,
      },
      {
        path: 'new-quiz',
        loader: mustHaveActiveDictionary,
        element: <QuizForm/>,
      },
      {
        path: 'quiz',
        loader: mustHaveActiveDictionary,
        element: <Quiz/>,
      },
    ],
  },
  {
    path: '/login',
    element: <Login/>,
  },
]);
