import { Outlet } from "react-router";
import Header from "../components/header";
import TabBar from "../components/tab-bar.jsx";

export default function Root() {
  return (
    <div className="bg-dark-950 min-h-full flex flex-col">
      <Header></Header>
      <main className="container mx-auto grow px-4 pb-16">
        <Outlet />
      </main>
      <TabBar></TabBar>
    </div>
  );
}
