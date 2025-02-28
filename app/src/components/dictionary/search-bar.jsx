import clsx from "clsx";
import { useEffect } from "react";
import { search } from "../../api/dictionary.js";
import { useSearchStore } from "../../stores/search.js";
import Input from "../forms/input.jsx";
import SearchResults from "./search-results.jsx";

export default function SearchBar(props) {
  const query = useSearchStore((state) => state.query);
  const results = useSearchStore((state) => state.results);
  const setQuery = useSearchStore((state) => state.setQuery);
  const setResults = useSearchStore((state) => state.setResults);

  useEffect(() => {
    if (!query) {
      return;
    }

    const controller = new AbortController();
    search(query, { signal: controller.signal }).then(setResults);

    return () => {
      controller.abort();
    };
  }, [query]);

  const onChange = (e) => {
    setQuery(e.target.value);

    if (!e.target.value) {
      setResults([]);
    }
  };

  return (
    <>
      <Input
        type="text"
        placeholder="Search..."
        className={clsx("w-full px-4 py-3", props.className ?? "")}
        value={query}
        onChange={onChange}
      />
      <SearchResults results={results} />
    </>
  );
}
