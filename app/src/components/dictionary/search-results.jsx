import { Fragment } from "react";
import SearchResult from "./search-result.jsx";

export default function SearchResults({ results }) {
  if (!results.length) {
    return null;
  }

  return (
    <div>
      {results.map((entry, index) => (
        <Fragment key={`search-result-${entry.id}`}>
          <SearchResult entry={entry} />
          {index < results.length - 1 && <hr className="my-3" />}
        </Fragment>
      ))}
    </div>
  );
}
