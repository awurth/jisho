import clsx from "clsx";
import { useNavigate } from "react-router";
import { useSearchStore } from "../../stores/search.js";

export default function SearchResult({ entry }) {
  const navigate = useNavigate();
  const resetSearch = useSearchStore((state) => state.reset);

  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  const senses = entry.senses;

  const onClick = () => {
    resetSearch();
    navigate(`/entry/${entry.id}`);
  };

  return (
    <div className="p-2 cursor-pointer" onClick={onClick}>
      <p className="text-xl font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-md text-gray-400 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-xs text-gray-500 font-semibold mb-1">
        {entry.readings[0].romaji}
      </p>
      <ul className="italic text-sm">
        {senses.map((sense, senseIndex) => (
          <li
            key={`search-result-sense-${senseIndex}`}
            className={clsx({ "mb-1": senseIndex < senses.length - 1 })}
          >
            -{" "}
            {sense.translations.map((translation, translationIndex) => (
              <span
                key={`search-result-sense-${senseIndex}-translation-${translationIndex}`}
              >
                {translation.value}
                {translationIndex === sense.translations.length - 1 ? "" : ", "}
              </span>
            ))}
          </li>
        ))}
      </ul>
    </div>
  );
}
