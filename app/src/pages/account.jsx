import { useNavigate } from "react-router";
import Button from "../components/button.jsx";
import PageContainer from "../components/page-container.jsx";
import { useUserStore } from "../stores/user.js";

export default function Account() {
  const navigate = useNavigate();
  const setUser = useUserStore((state) => state.setUser);

  const logout = () => {
    setUser(null);
    navigate("/login");
  };

  return (
    <PageContainer>
      <Button size="block" onClick={logout}>
        Log out
      </Button>
    </PageContainer>
  );
}
