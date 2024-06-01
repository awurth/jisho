import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useParams } from "react-router-dom";
import { postDeckEntry } from "../api/deck.js";
import { getEntry } from "../api/dictionary.js";
import Button from "../components/button.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function Entry() {
  const { id } = useParams();
  const activeDeck = useDeckStore((state) => state.activeDeck);

  const { isPending, data: entry = {} } = useQuery({
    queryKey: ["entry", id],
    queryFn: () => getEntry(id),
  });

  const queryClient = useQueryClient();
  const mutation = useMutation({
    mutationFn: ({ deckId, entryId }) => postDeckEntry(deckId, entryId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["entries"] });
    },
  });

  if (isPending) {
    return <></>;
  }

  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  return (
    <>
      <p className="text-gray-100 text-3xl font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-lg text-gray-300 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-md text-gray-300 mb-1">{entry.readings[0].romaji}</p>
      <ul className="mb-3">
        {entry.senses.map((sense, index) => (
          <li key={index} className="text-gray-100">
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
      <Button
        className="px-3 py-2"
        onClick={() => mutation.mutate({ deckId: activeDeck.id, entryId: id })}
      >
        Ajouter à mon jeu
      </Button>
    </>
  );
}
