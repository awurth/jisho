import { useQuery } from "@tanstack/react-query";
import {Link} from 'react-router-dom';
import { getEntries } from "../api/deck.js";
import Entry from "../components/deck/entry.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function Deck() {
  const deck = useDeckStore((state) => state.activeDeck);

  if (!deck) {
    return (
      <div className="text-center mt-20">
        <p className="text-white mb-3">Pas de jeu de cartes sélectionné. Sélectionnez-en un ou</p>
        <Link to="/new-deck" className="inline-block bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-5 py-3">Créer un jeu de cartes</Link>
      </div>
    );
  }

  const { data: entries = [] } = useQuery({
    queryKey: ["entries", deck.id],
    queryFn: () => getEntries(deck.id),
  });

  return (
    <>
      <div className="flex flex-col">
        {entries.map((entry) => (
          <Entry key={entry.japanese} entry={entry} className="mb-4" />
        ))}
      </div>
    </>
  );
}
