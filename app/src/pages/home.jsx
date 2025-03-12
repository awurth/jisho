import { useQuery } from "@tanstack/react-query";
import { Fragment } from "react";
import { getCards } from "../api/deck.js";
import Button from "../components/button.jsx";
import Card from "../components/deck/card.jsx";
import PageContainer from "../components/page-container.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function Home() {
  const deck = useDeckStore((state) => state.activeDeck);

  if (!deck) {
    return (
      <PageContainer className="text-center">
        <p className="mt-20 mb-10 text-sm">
          You don't have a deck of cards yet
        </p>
        <Button href="/new-deck" size="large">
          Create a deck of cards
        </Button>
      </PageContainer>
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
      <PageContainer>
        <p className="mt-20 mb-3 px-5 text-sm text-center">
          You haven't added any cards to your deck yet.
          <br />
          Use the search bar to find a word and add it to your deck.
        </p>
      </PageContainer>
    );
  }

  return (
    <PageContainer>
      <h1 className="text-lg font-semibold mb-2">Your cards</h1>
      <div className="flex flex-col">
        {cards.map((card, index) => (
          <Fragment key={card.id}>
            <Card entry={card.entry} />
            {index < cards.length - 1 && (
              <hr className="my-3 border-gray-300" />
            )}
          </Fragment>
        ))}
      </div>
    </PageContainer>
  );
}
