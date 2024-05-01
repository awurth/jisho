import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { logout } from "../api/user.js";
import Button from "../components/button.jsx";

export default function Account() {
  const navigate = useNavigate();

  const mutation = useMutation({
    mutationFn: logout,
    onSettled: () => {
      navigate("/login");
    },
  });

  return (
    <>
      <Button className="w-full py-4" onClick={() => mutation.mutate()}>
        Déconnexion
      </Button>
    </>
  );
}
