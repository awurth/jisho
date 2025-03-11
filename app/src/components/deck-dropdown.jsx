import { faCaretDown, faPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Menu } from "@headlessui/react";
import { useQuery } from "@tanstack/react-query";
import { Link, useNavigate } from "react-router";
import { getDecks } from "../api/deck.js";
import { useDeckStore } from "../stores/deck.js";

export default function DeckDropdown() {
  const navigate = useNavigate();
  const setActiveDeck = useDeckStore((state) => state.setActiveDeck);
  const activeDeck = useDeckStore((state) => state.activeDeck);

  const { data: decks = [] } = useQuery({
    queryKey: ["decks"],
    queryFn: getDecks,
  });

  const onDeckClick = (deck) => {
    setActiveDeck(deck);
    navigate("/");
  };

  return (
    <Menu as="div" className="relative">
      <Menu.Button className="px-3 py-2 rounded-md font-semibold">
        <span className="mr-1">{activeDeck?.name}</span>
        <FontAwesomeIcon icon={faCaretDown} />
      </Menu.Button>
      <Menu.Items className="absolute bg-white px-2 py-3 mt-1 rounded-md shadow-lg min-w-40">
        {decks.map(({ id, name }) => (
          <Menu.Item
            key={id}
            className="px-4 py-2 mb-1 hover:bg-gray-100 rounded-md cursor-pointer"
          >
            <div onClick={() => onDeckClick({ id, name })}>{name}</div>
          </Menu.Item>
        ))}
        <Menu.Item className="block px-4 py-2 hover:bg-gray-100 rounded-md cursor-pointer whitespace-nowrap">
          <Link to="/new-deck">
            <FontAwesomeIcon icon={faPlus} className="mr-1" />
            New
          </Link>
        </Menu.Item>
      </Menu.Items>
    </Menu>
  );
}
