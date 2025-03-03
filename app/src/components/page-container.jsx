import clsx from "clsx";
import { useSearchStore } from "../stores/search.js";

export default function PageContainer({ className, ...props }) {
  const results = useSearchStore((state) => state.results);

  return (
    <main
      className={clsx("relative container mx-auto grow px-5 pb-16", className, {
        hidden: results.length,
      })}
      {...props}
    />
  );
}
