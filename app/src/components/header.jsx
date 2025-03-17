import { faPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from "react-router";
import { useDeckStore } from "../stores/deck.js";
import { useUserStore } from "../stores/user.js";
import DeckDropdown from "./deck-dropdown.jsx";
import SearchBar from "./dictionary/search-bar.jsx";

export default function Header() {
  const user = useUserStore((state) => state.user);
  const activeDeck = useDeckStore((state) => state.activeDeck);

  return (
    <header>
      <div className="container mx-auto flex px-5 my-3 justify-between items-center">
        {activeDeck ? (
          <DeckDropdown />
        ) : (
          <div>
            <Link
              to="/jisho/new-deck"
              className="text-white bg-black/15 hover:bg-black/20 px-3 py-2 rounded-md"
            >
              <FontAwesomeIcon icon={faPlus} />
            </Link>
          </div>
        )}
        <Link to="/jisho/account" className="rounded-full">
          <img
            alt="avatar"
            className="w-10 h-10 object-cover object-center rounded-full shadow-md inline-block"
            src={user.avatarUrl}
          />
        </Link>
      </div>
      <div className="container mx-auto px-5 mb-3">
        <SearchBar />
      </div>
    </header>
  );
}
