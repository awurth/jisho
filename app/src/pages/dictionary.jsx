import { useQuery } from "@tanstack/react-query";
import clsx from "clsx";
import { getEntries } from "../api/dictionary.js";
import AddEntry from "../components/dictionary/add-entry.jsx";
import Entry from "../components/dictionary/entry.jsx";
import useBodyClick from "../hooks/useBodyClick.js";
import { useDictionaryStore } from "../stores/dictionary.js";
import { useEntryFormStore } from "../stores/entry-form.js";

export default function Dictionary() {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const entryFormVisible = useEntryFormStore((state) => state.visible);
  const setEntryFormVisible = useEntryFormStore((state) => state.setVisible);

  useBodyClick([".add-entry", ".add-entry-button"], () =>
    setEntryFormVisible(false),
  );

  const { data: entries = [] } = useQuery({
    queryKey: ["entries", dictionary.id],
    queryFn: () => getEntries(dictionary.id),
  });

  return (
    <>
      <AddEntry
        className={clsx({ "add-entry mb-4": true, hidden: !entryFormVisible })}
      />
      <div className="flex flex-col">
        {entries.map((entry) => (
          <Entry key={entry.japanese} entry={entry} className="mb-4" />
        ))}
      </div>
    </>
  );
}
