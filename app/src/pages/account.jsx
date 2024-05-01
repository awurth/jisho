import axios from 'axios';
import {useEffect, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import Button from '../components/button.jsx';

export default function Account() {
  const [logoutClicked, setLogoutClicked] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    if (!logoutClicked) {
      return;
    }

    axios.get('/api/logout').finally(() => {
      navigate('/login');
    });
  }, [logoutClicked]);

  return (
    <>
      <Button className="w-full py-4" onClick={() => setLogoutClicked(true)}>Déconnexion</Button>
    </>
  );
}
