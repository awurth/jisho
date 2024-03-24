import axios from 'axios';
import React from 'react';
import {createBrowserRouter, redirect} from 'react-router-dom';
import Error from './error.jsx';
import Dictionaries from './pages/dictionaries.jsx';
import Dictionary from './pages/dictionary.jsx';
import Login from './pages/login.jsx';
import Root from './pages/root.jsx';
import {useDictionaryStore} from './stores/dictionary.js';
import {useUserStore} from './stores/user.js';

const mustBeLoggedIn = async () => {
  try {
    const user = await axios.get('/api/me');
    useUserStore.setState({user: user.data});
  } catch (error) {
    useUserStore.setState({user: null});
    return redirect('/login');
  }

  return null;
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
    element: <Root/>,
    errorElement: <Error/>,
    loader: mustBeLoggedIn,
    children: [
      {
        index: true,
        loader: mustHaveActiveDictionary,
        element: <Dictionary/>,
      },
      {
        path: 'dictionaries',
        element: <Dictionaries/>,
      },
    ],
  },
  {
    path: '/login',
    element: <Login/>,
  },
]);
