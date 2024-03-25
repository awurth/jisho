import clsx from 'clsx';
import {useEffect, useState} from 'react';
import {Link, NavLink} from 'react-router-dom';
import {useUserStore} from '../stores/user.js';

export default function Header() {
  const {name, avatarUrl} = useUserStore((state) => state.user);
  const [dropdownOpen, setDropdownOpen] = useState(false);

  const onBodyClick = (event) => {
    if (!event.target.closest('.dropdown')) {
      setDropdownOpen(false);
    }
  };

  useEffect(() => {
    document.addEventListener('click', onBodyClick);

    return () => {
      document.removeEventListener('click', onBodyClick);
    };
  }, []);

  return (
    <header className="text-gray-600">
      <div className="container mx-auto flex flex-wrap p-5 items-center">
        <Link to="/" className="flex title-font font-medium items-center text-gray-900 mb-0">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" strokeLinecap="round"
               strokeLinejoin="round" strokeWidth="2" className="w-10 h-10 text-white p-2 bg-red-500 rounded-full"
               viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
          </svg>
          <span className="ml-3 text-xl">Jish.io</span>
        </Link>
        <nav className="ml-auto flex flex-wrap items-center text-base justify-center">
          <NavLink to="dictionaries" className="mr-5">Dictionnaires</NavLink>
          <a className="mr-5">Quiz</a>
        </nav>
        <div className="dropdown relative">
          <div className="cursor-pointer" onClick={() => setDropdownOpen(!dropdownOpen)}>
            <span className="mr-2">{name}</span>
            <img alt="avatar"
                 className="w-10 h-10 object-cover object-center rounded-full inline-block"
                 src={avatarUrl}/>
          </div>
          <ul className={clsx('absolute top-0 right-0 mt-11 bg-white rounded-lg shadow-lg p-2', {hidden: !dropdownOpen})}>
            <li className="cursor-pointer p-2 rounded hover:bg-gray-100">
              <Link to="/logout">Déconnexion</Link>
            </li>
          </ul>
        </div>
      </div>
    </header>
  );
}
