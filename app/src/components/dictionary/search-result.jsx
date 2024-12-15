import { useNavigate } from "react-router";

export default function SearchResult({ entry }) {
  const navigate = useNavigate();
  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  const senses = entry.senses;

  return (
    <div
      className="bg-dark-950 rounded-lg text-white p-2 mb-2"
      onClick={() => navigate(`/entry/${entry.id}`)}
    >
      <p className="text-lg font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-sm text-gray-300 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-xs text-gray-300 mb-1">{entry.readings[0].romaji}</p>
      <ul>
        {senses.map((sense, index) => (
          <li key={index} className="mb-1">
            -{" "}
            {sense.translations.map((translation, index) => (
              <span key={index}>
                {translation.value}
                {index === sense.translations.length - 1 ? "" : ", "}
              </span>
            ))}
          </li>
        ))}
      </ul>
    </div>
  );
}
