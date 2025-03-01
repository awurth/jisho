import { useNavigate } from "react-router";
import Button from "../components/button.jsx";
import {useUserStore} from '../stores/user.js';

export default function Account() {
  const navigate = useNavigate();
  const setUser = useUserStore((state) => state.setUser);

  const logout = () => {
    setUser(null);
    navigate("/login");
  };

  return (
    <>
      <Button size="block" onClick={logout}>Log out</Button>
    </>
  );
}
