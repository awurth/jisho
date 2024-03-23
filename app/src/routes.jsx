import axios from 'axios';
import React from 'react';
import {createBrowserRouter, redirect} from 'react-router-dom';
import Error from './error.jsx';
import Login from './pages/login.jsx';
import Root from './pages/root.jsx';

const mustBeLoggedIn = () => {
  axios.get('https://jish.io/api/me');
  return redirect('/login');
};

export const router = createBrowserRouter([
  {
    path: '/',
    element: <Root/>,
    errorElement: <Error/>,
    loader: mustBeLoggedIn,
    children: [],
  },
  {
    path: '/login',
    element: <Login/>,
  },
]);
