import { useEffect } from "react";
import { useNavigate } from "react-router";
import { useDeckStore } from "../stores/deck.js";
import { useUserStore } from "../stores/user.js";

export default function Logout() {
  const navigate = useNavigate();
  const setUser = useUserStore((state) => state.setUser);
  const setActiveDeck = useDeckStore((state) => state.setActiveDeck);

  useEffect(() => {
    localStorage.removeItem("token");

    setActiveDeck(null);
    setUser(null);
    navigate("/jisho/login");
  });

  return <></>;
}
