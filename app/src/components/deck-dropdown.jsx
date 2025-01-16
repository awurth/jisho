import { faCaretDown, faPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Menu } from "@headlessui/react";
import { useQuery } from "@tanstack/react-query";
import {Link} from 'react-router';
import { getDecks } from "../api/deck.js";
import { useDeckStore } from "../stores/deck.js";

export default function DeckDropdown() {
  const setActiveDeck = useDeckStore((state) => state.setActiveDeck);
  const activeDeck = useDeckStore((state) => state.activeDeck);

  const { data: decks = [] } = useQuery({
    queryKey: ["decks"],
    queryFn: getDecks,
  });

  const onDeckClick = (deck) => {
    setActiveDeck(deck);
  };

  return (
    <Menu as="div" className="relative">
      <Menu.Button className="text-white bg-black/15 hover:bg-black/20 px-3 py-2 rounded-md">
        <span className="mr-1">{activeDeck?.name}</span>
        <FontAwesomeIcon icon={faCaretDown} />
      </Menu.Button>
      <Menu.Items className="absolute bg-white px-2 py-3 mt-1 rounded-md shadow-lg">
        {decks.map(({ id, name }) => (
          <Menu.Item
            key={id}
            className="px-4 py-2 hover:bg-gray-100 rounded-md cursor-pointer"
          >
            <div onClick={() => onDeckClick({ id, name })}>{name}</div>
          </Menu.Item>
        ))}
        <Menu.Item className="px-4 py-2 hover:bg-gray-100 rounded-md cursor-pointer whitespace-nowrap">
          <Link to="/new-deck">
            <FontAwesomeIcon icon={faPlus} className="mr-1" />
            New
          </Link>
        </Menu.Item>
      </Menu.Items>
    </Menu>
  );
}
