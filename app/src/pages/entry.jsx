import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useParams } from "react-router";
import { postCard } from "../api/deck.js";
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
    mutationFn: ({ deckId, entryId }) => postCard(deckId, entryId),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["cards"] });
    },
  });

  if (isPending) {
    return <></>;
  }

  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  return (
    <>
      <p className="text-3xl font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && (
        <p className="text-lg text-gray-400 mb-1">{entry.readings[0].kana}</p>
      )}
      <p className="text-md text-gray-500 mb-1">{entry.readings[0].romaji}</p>
      <ul className="mb-3 italic">
        {entry.senses.map((sense, index) => (
          <li key={index}>
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
      <Button
        className="px-3 py-2"
        onClick={() => mutation.mutate({ deckId: activeDeck.id, entryId: id })}
      >
        Add to deck
      </Button>
    </>
  );
}
