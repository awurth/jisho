import {faBookOpen} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Link} from 'react-router-dom';
import {useDictionaryStore} from '../stores/dictionary.js';
import {useUserStore} from '../stores/user.js';
import DictionaryDropdown from './dictionary-dropdown.jsx';

export default function Header() {
  const {name, avatarUrl} = useUserStore((state) => state.user);
  const dictionary = useDictionaryStore((state) => state.activeDictionary);

  return (
    <header>
      <div className="container mx-auto flex px-5 py-3 justify-between items-center">
        <DictionaryDropdown/>
        {/*<div className="flex flex-col">*/}
        {/*  <span className="text-white font-semibold">{name}-さん</span>*/}
        {/*  <span className="text-white font-bold text-xs">おはようございます！</span>*/}
        {/*  {dictionary && <div className="text-white text-sm">*/}
        {/*    <FontAwesomeIcon icon={faBookOpen} className="mr-1"/>*/}
        {/*    <span>{dictionary.name}</span>*/}
        {/*  </div>}*/}
        {/*</div>*/}
        <Link to="/account" className="rounded-full">
          <img alt="avatar" className="border-4 border-primary-700 w-12 h-12 object-cover object-center rounded-full shadow-md inline-block" src={avatarUrl}/>
        </Link>
      </div>
    </header>
  );
}
