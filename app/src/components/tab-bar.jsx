import { faCircleQuestion, faUser } from "@fortawesome/free-regular-svg-icons";
import { faHome, faSearch } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import clsx from "clsx";
import { useCallback } from "react";
import { NavLink } from "react-router";

export default function TabBar() {
  const itemClassName = useCallback(
    ({ isActive }) =>
      clsx({
        "inline-flex flex-col px-4 pt-5 pb-3 text-gray-600": true,
        "text-gray-950": isActive,
        "hover:text-gray-950": !isActive,
      }),
    [],
  );

  return (
    <footer className="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl">
      <ul className="grid grid-cols-3 text-center">
        <li>
          <NavLink to="/" className={itemClassName}>
            <FontAwesomeIcon icon={faHome} className="mb-1" />
            <span className="text-xs">Home</span>
          </NavLink>
        </li>
        {/*<li>*/}
        {/*  <NavLink to="/search" className={itemClassName}>*/}
        {/*    <FontAwesomeIcon icon={faSearch} className="mb-1" />*/}
        {/*    <span className="text-xs">Search</span>*/}
        {/*  </NavLink>*/}
        {/*</li>*/}
        <li>
          <NavLink to="/new-quiz" className={itemClassName}>
            <FontAwesomeIcon icon={faCircleQuestion} className="mb-1" />
            <span className="text-xs">Quiz</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/account" className={itemClassName}>
            <FontAwesomeIcon icon={faUser} className="mb-1" />
            <span className="text-xs">Account</span>
          </NavLink>
        </li>
      </ul>
    </footer>
  );
}
