import React from 'react';
import ReactDOM from 'react-dom/client';
import {createBrowserRouter, redirect, RouterProvider} from 'react-router-dom';
import axios from 'axios';
import Root from './pages/root';
import Login from './pages/login';
import Error from './error';
import './index.css';

const mustBeLoggedIn = () => {
  axios.get('https://jish.io/api/me');
  return redirect('/login');
};

const router = createBrowserRouter([
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

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <RouterProvider router={router}/>
  </React.StrictMode>,
);
