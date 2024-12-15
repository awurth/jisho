import { useQuery } from "@tanstack/react-query";
import { Link } from "react-router";
import { getCards } from "../api/deck.js";
import Card from "../components/deck/card.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function Home() {
  const deck = useDeckStore((state) => state.activeDeck);

  if (!deck) {
    return (
      <div className="text-center mt-20">
        <p className="text-white mb-3">
          Vous n'avez pas encore de jeu de cartes
        </p>
        <Link
          to="/new-deck"
          className="inline-block bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-5 py-3"
        >
          Créer un jeu de cartes
        </Link>
      </div>
    );
  }

  const { isPending, data: cards = [] } = useQuery({
    queryKey: ["cards", deck.id],
    queryFn: () => getCards(deck.id),
  });

  if (isPending) {
    return <></>;
  }

  if (!cards.length) {
    return (
      <div className="text-center mt-20">
        <p className="text-white mb-3">
          Vous n'avez pas encore ajouté de cartes à votre jeu
        </p>
        <Link
          to="/search"
          className="inline-block bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-5 py-3"
        >
          Ajouter des mots
        </Link>
      </div>
    );
  }

  return (
    <>
      <div className="flex flex-col">
        {cards.map((card) => (
          <Card key={card.id} entry={card.entry} className="mb-4" />
        ))}
      </div>
    </>
  );
}
