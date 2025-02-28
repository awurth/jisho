import clsx from "clsx";
import { Outlet } from "react-router";
import Header from "../components/header";
import TabBar from "../components/tab-bar.jsx";
import { useSearchStore } from "../stores/search.js";

export default function Root() {
  const results = useSearchStore((state) => state.results);

  return (
    <div className="bg-gray-50 min-h-full flex flex-col">
      <Header></Header>
      <main
        className={clsx("container mx-auto grow px-5 pb-16", {
          hidden: results.length,
        })}
      >
        <Outlet />
      </main>
      <TabBar></TabBar>
    </div>
  );
}
