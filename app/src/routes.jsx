import axios from 'axios';
import React from 'react';
import {createBrowserRouter, redirect} from 'react-router-dom';
import Error from './error.jsx';
import Dictionaries from './pages/dictionaries.jsx';
import Login from './pages/login.jsx';
import Root from './pages/root.jsx';

const mustBeLoggedIn = async () => {
  try {
    const user = await axios.get('/api/me');
  } catch (error) {
    return redirect('/login');
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
        loader: () => redirect('/dictionaries'),
      },
      {
        path: 'dictionaries',
        element: <Dictionaries/>,
      }
    ],
  },
  {
    path: '/login',
    element: <Login/>,
  },
]);
