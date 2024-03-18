import React from 'react';
import ReactDOM from 'react-dom/client';
import {createBrowserRouter, redirect, RouterProvider} from 'react-router-dom';
import axios from 'axios';
import Root from './routes/root';
import Error from './error';
import Login from './routes/login';
import './index.css';

const mustBeLoggedIn = () => {
  axios.get('https://localhost/api/me');
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
