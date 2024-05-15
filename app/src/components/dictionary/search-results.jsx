import SearchResult from './search-result.jsx';

export default function SearchResults({results}) {
  if (!results.length) {
    return null;
  }

  return (
    <div className="bg-dark-900 rounded-lg p-2">
      {results.map((entry) => (
        <SearchResult key={entry.id} entry={entry}/>
      ))}
    </div>
  );
}
