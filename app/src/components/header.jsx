import {faPlus} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {useQuery} from '@tanstack/react-query';
import { Link } from "react-router-dom";
import {getDecks} from '../api/deck.js';
import { useUserStore } from "../stores/user.js";
import DeckDropdown from "./deck-dropdown.jsx";

export default function Header() {
  const { avatarUrl } = useUserStore((state) => state.user);

  const { data: decks = [] } = useQuery({
    queryKey: ["decks"],
    queryFn: getDecks,
  });

  return (
    <header>
      <div className="container mx-auto flex px-5 py-3 justify-between items-center">
        {decks.length ? <DeckDropdown/> : <div>
          <Link to="/new-deck" className="text-white bg-black/15 hover:bg-black/20 px-3 py-2 rounded-md">
            <FontAwesomeIcon icon={faPlus}/>
          </Link>
        </div>}
        <Link to="/account" className="rounded-full">
          <img
            alt="avatar"
            className="border-4 border-dark-900 w-12 h-12 object-cover object-center rounded-full shadow-md inline-block"
            src={avatarUrl}
          />
        </Link>
      </div>
    </header>
  );
}
