import clsx from "clsx";
import { useEffect, useState } from "react";
import { search } from "../../api/dictionary.js";
import Input from "../forms/input.jsx";
import SearchResults from "./search-results.jsx";

export default function SearchBar(props) {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState([]);

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

  return (
    <>
      <Input
        type="search"
        placeholder="Search..."
        className={clsx("w-full px-4 py-3", props.className ?? "")}
        value={query}
        onChange={(e) => setQuery(e.target.value)}
      />
      <SearchResults results={results} />
    </>
  );
}
