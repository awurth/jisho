import {faBookAtlas} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import clsx from 'clsx';
import {useState} from 'react';
import {Link, NavLink} from 'react-router-dom';
import useBodyClick from '../hooks/useBodyClick.js';
import {useUserStore} from '../stores/user.js';

export default function Header() {
  const {name, avatarUrl} = useUserStore((state) => state.user);
  const [dropdownOpen, setDropdownOpen] = useState(false);

  useBodyClick('.dropdown', () => setDropdownOpen(false));

  return (
    <header className="text-gray-600">
      <div className="container mx-auto flex flex-wrap px-5 py-3 items-center">
        <Link to="/" className="flex title-font font-medium items-center text-gray-600 mb-0 select-none">
          <FontAwesomeIcon icon={faBookAtlas} className="w-6 h-6 text-white p-2 bg-primary-400 rounded-full"/>
          <span className="ml-3 text-xl">Jish.io</span>
        </Link>
        <nav className="ml-auto flex flex-wrap items-center text-base justify-center">
          <a href="" className="bg-primary-400 hover:bg-primary-500 rounded-lg text-white px-5 py-2 mr-5">Quiz</a>
        </nav>
        <div className="dropdown relative">
          <div className="flex items-center cursor-pointer" onClick={() => setDropdownOpen(!dropdownOpen)}>
            <span className="mr-2">{name}</span>
            <img alt="avatar"
                 className="w-10 h-10 object-cover object-center rounded-full shadow-md inline-block"
                 src={avatarUrl}/>
          </div>
          <ul className={clsx('absolute top-0 right-0 mt-11 bg-white rounded-lg shadow-lg p-2', {hidden: !dropdownOpen})}>
            <li className="cursor-pointer p-2 mb-1 rounded hover:bg-gray-100">
              <NavLink to="dictionaries" onClick={() => setDropdownOpen(false)}>Dictionaries</NavLink>
            </li>
            <li className="cursor-pointer p-2 rounded hover:bg-gray-100">
              <Link to="/logout">Déconnexion</Link>
            </li>
          </ul>
        </div>
      </div>
    </header>
  );
}
