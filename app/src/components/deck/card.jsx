import clsx from "clsx";
import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Card({ entry, ...props }) {
  const navigate = useNavigate();
  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  const [sensesShown, setSensesShown] = useState(false);

  const onSensesToggleButtonClick = (e) => {
    e.stopPropagation();
    setSensesShown(!sensesShown);
  };

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
      <ul className="mb-1">
        {entry.senses.map((sense, index) => (
          <li
            key={index}
            className={clsx("mb-1", { hidden: index > 0 && !sensesShown })}
          >
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
      {entry.senses.length > 1 && (
        <div className="text-left text-sm text-gray-400 font-semibold">
          <button onClick={onSensesToggleButtonClick}>
            Afficher {sensesShown ? "moins" : "plus"}
          </button>
        </div>
      )}
      {/*<div className="flex flex-wrap">*/}
      {/*  {entry.tags.map((tag) => (*/}
      {/*    <Tag key={tag} name={tag} className="mr-1 mb-1" />*/}
      {/*  ))}*/}
      {/*</div>*/}
      {/*{!!entry.notes && <p className="text-xs text-gray-400">{entry.notes}</p>}*/}
    </div>
  );
}
