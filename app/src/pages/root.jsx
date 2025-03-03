import { Outlet } from "react-router";
import Header from "../components/header";
import TabBar from "../components/tab-bar.jsx";

export default function Root() {
  return (
    <div className="bg-gray-50 min-h-full flex flex-col">
      <Header />
      <Outlet />
      <TabBar />
    </div>
  );
}
