import clsx from "clsx";
import { toRomaji } from "wanakana";
import Tag from "./tag.jsx";

export default function Entry({ entry, ...props }) {
  return (
    <div
      className={clsx(
        "border-2 border-b-4 border-dark-900 hover:border-secondary-400 rounded-xl px-5 py-4 flex flex-col text-gray-200",
        props.className ?? "",
      )}
    >
      <p className="text-xl font-bold">{entry.japanese}</p>
      <p className="text-xs text-gray-600 font-semibold">
        {toRomaji(entry.japanese)}
      </p>
      <p className="mb-1">{entry.french.join(", ")}</p>
      <div className="flex flex-wrap">
        {entry.tags.map((tag) => (
          <Tag key={tag} name={tag} className="mr-1 mb-1" />
        ))}
      </div>
    </div>
  );
}
