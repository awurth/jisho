import { useNavigate } from "react-router";
import Button from "../components/button.jsx";
import PageContainer from "../components/page-container.jsx";

export default function Account() {
  const navigate = useNavigate();

  const logout = () => {
    navigate("/jisho/logout");
  };

  return (
    <PageContainer>
      <Button size="block" onClick={logout}>
        Log out
      </Button>
    </PageContainer>
  );
}
