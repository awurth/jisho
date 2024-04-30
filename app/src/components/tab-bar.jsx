import {faUser} from '@fortawesome/free-regular-svg-icons';
import {faDumbbell, faPlus, faSearch, faToriiGate} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import clsx from 'clsx';
import {useCallback} from 'react';
import {NavLink, useNavigate} from 'react-router-dom';
import {useEntryFormStore} from '../stores/entry-form.js';

export default function TabBar() {
  const navigate = useNavigate();
  const setEntryFormVisible = useEntryFormStore((state) => state.setVisible);

  const itemClassName = useCallback(({isActive}) => clsx({
    'inline-flex flex-col px-4 py-3': true,
    'text-gray-700': isActive,
    'hover:text-gray-600': !isActive,
  }), []);

  const onAddButtonClick = () => {
    setEntryFormVisible(true);
    navigate('/');
  };

  return (
    <footer className="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl text-gray-400" style={{boxShadow: '0 -5px 20px 0 rgba(150,150,150,0.2)'}}>
      <ul className="grid grid-cols-5 text-center">
        <li>
          <NavLink to="/" className={itemClassName}>
            <FontAwesomeIcon icon={faToriiGate} className="mb-1"/>
            <span className="text-xs">Accueil</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/" className={itemClassName}>
            <FontAwesomeIcon icon={faSearch} className="mb-1"/>
            <span className="text-xs">Recherche</span>
          </NavLink>
        </li>
        <li className="flex items-center justify-center">
          <button onClick={onAddButtonClick} className="add-entry-button flex items-center justify-center bg-primary-400 hover:bg-primary-500 text-white rounded-full w-10 h-10">
            <FontAwesomeIcon icon={faPlus}/>
          </button>
        </li>
        <li>
          <NavLink to="/new-quiz" className={itemClassName}>
            <FontAwesomeIcon icon={faDumbbell} className="mb-1"/>
            <span className="text-xs">Quiz</span>
          </NavLink>
        </li>
        <li>
          <NavLink to="/account" className={itemClassName}>
            <FontAwesomeIcon icon={faUser} className="mb-1"/>
            <span className="text-xs">Compte</span>
          </NavLink>
        </li>
      </ul>
    </footer>
  );
}
