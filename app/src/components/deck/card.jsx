import clsx from "clsx";
import { useState } from "react";
import { useNavigate } from "react-router";

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
        "p-2",
        props.className ?? "",
      )}
      onClick={() => navigate(`/entry/${entry.id}`)}
    >
      <p className="text-xl font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-md text-gray-400 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-xs text-gray-500 font-semibold mb-1">{entry.readings[0].romaji}</p>
      <ul className="italic text-sm">
        {entry.senses.map((sense, index) => (
          <li
            key={index}
            className={clsx({ "mb-1": index < entry.senses.length - 1, hidden: index > 0 && !sensesShown })}
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
            Show {sensesShown ? "less" : "more"}
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
