import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router";
import Button from "../components/button.jsx";

export default function Account() {
  const navigate = useNavigate();

  // const mutation = useMutation({
  //   mutationFn: logout,
  //   onSettled: () => {
  //     navigate("/login");
  //   },
  // });

  return (
    <>
      <Button className="w-full py-4">Log out</Button>
    </>
  );
}
