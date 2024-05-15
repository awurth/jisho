import { faCircleQuestion, faUser } from "@fortawesome/free-regular-svg-icons";
import { faHome, faPlus, faSearch } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import clsx from "clsx";
import { useCallback } from "react";
import { NavLink } from "react-router-dom";

export default function TabBar() {
  const itemClassName = useCallback(
    ({ isActive }) =>
      clsx({
        "inline-flex flex-col px-4 py-3 text-gray-300": true,
        "text-gray-50": isActive,
        "hover:text-gray-400": !isActive,
      }),
    [],
  );

  return (
    <footer className="fixed bottom-0 left-0 right-0 bg-dark-950 border-l-1 border-r-1 border-t-2 border-dark-900 rounded-t-2xl">
      <ul className="grid grid-cols-4 text-center">
        <li>
          <NavLink to="/" className={itemClassName}>
            <FontAwesomeIcon icon={faHome} className="mb-1" />
            <span className="text-xs">Accueil</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/search" className={itemClassName}>
            <FontAwesomeIcon icon={faSearch} className="mb-1" />
            <span className="text-xs">Recherche</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/new-quiz" className={itemClassName}>
            <FontAwesomeIcon icon={faCircleQuestion} className="mb-1" />
            <span className="text-xs">Quiz</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/account" className={itemClassName}>
            <FontAwesomeIcon icon={faUser} className="mb-1" />
            <span className="text-xs">Compte</span>
          </NavLink>
        </li>
      </ul>
    </footer>
  );
}
