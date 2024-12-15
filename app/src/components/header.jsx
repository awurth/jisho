import { faPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from "react-router";
import { useDeckStore } from "../stores/deck.js";
import { useUserStore } from "../stores/user.js";
import DeckDropdown from "./deck-dropdown.jsx";

export default function Header() {
  const { avatarUrl } = useUserStore((state) => state.user);

  const activeDeck = useDeckStore((state) => state.activeDeck);

  return (
    <header>
      <div className="container mx-auto flex px-5 py-3 justify-between items-center">
        {activeDeck ? (
          <DeckDropdown />
        ) : (
          <div>
            <Link
              to="/new-deck"
              className="text-white bg-black/15 hover:bg-black/20 px-3 py-2 rounded-md"
            >
              <FontAwesomeIcon icon={faPlus} />
            </Link>
          </div>
        )}
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
