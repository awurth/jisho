import clsx from "clsx";
import { useNavigate } from "react-router-dom";

export default function Entry({ entry, ...props }) {
  const navigate = useNavigate();
  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  return (
    <div
      className={clsx(
        "border-2 border-b-4 border-dark-900 hover:border-secondary-400 rounded-xl px-5 py-4 flex flex-col text-gray-200",
        props.className ?? "",
      )}
      onClick={() => navigate(`/entry/${entry.id}`)}
    >
      <p className="text-xl font-bold">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-sm text-gray-300 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-xs text-gray-300 mb-1">{entry.readings[0].romaji}</p>
      <ul>
        {entry.senses.map((sense, index) => (
          <li key={index}>
            -{" "}
            {sense.translations.map((translation, index) => (
              <span key={index}>
                <span className="inline-block bg-dark-900 rounded px-1 mr-1">
                  {translation.language === "fre" ? "fr" : "en"}
                </span>
                {translation.value}
                {index === sense.translations.length - 1 ? "" : ", "}
              </span>
            ))}
          </li>
        ))}
      </ul>
      {/*<p className="text-xs text-gray-600 font-semibold">*/}
      {/*  {toRomaji(entry.japanese)}*/}
      {/*</p>*/}
      {/*<p className="mb-1">{entry.french.join(", ")}</p>*/}
      {/*<div className="flex flex-wrap">*/}
      {/*  {entry.tags.map((tag) => (*/}
      {/*    <Tag key={tag} name={tag} className="mr-1 mb-1" />*/}
      {/*  ))}*/}
      {/*</div>*/}
      {/*{!!entry.notes && <p className="text-xs text-gray-400">{entry.notes}</p>}*/}
    </div>
  );
}
