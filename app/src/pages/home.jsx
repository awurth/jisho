import { useQuery } from "@tanstack/react-query";
import { Fragment } from "react";
import { Link } from "react-router";
import { getCards } from "../api/deck.js";
import Card from "../components/deck/card.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function Home() {
  const deck = useDeckStore((state) => state.activeDeck);

  if (!deck) {
    return (
      <div className="text-center mt-20">
        <p className="text-white mb-3">You don't have a deck of cards yet</p>
        <Link
          to="/new-deck"
          className="inline-block bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-5 py-3"
        >
          Create a deck of cards
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
          You haven't added any cards to your deck yet
        </p>
        <Link
          to="/search"
          className="inline-block bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-5 py-3"
        >
          Add cards
        </Link>
      </div>
    );
  }

  return (
    <>
      <h1 className="text-xl font-semibold mb-2">Your cards</h1>
      <div className="flex flex-col">
        {cards.map((card, index) => (
          <Fragment key={card.id}>
            <Card entry={card.entry} />
            {index < cards.length - 1 && <hr className="my-3" />}
          </Fragment>
        ))}
      </div>
    </>
  );
}
