import {Link} from 'react-router-dom';
import {useUserStore} from '../stores/user.js';
import DictionaryDropdown from './dictionary-dropdown.jsx';

export default function Header() {
  const {avatarUrl} = useUserStore((state) => state.user);

  return (
    <header>
      <div className="container mx-auto flex px-5 py-3 justify-between items-center">
        <DictionaryDropdown/>
        <Link to="/account" className="rounded-full">
          <img alt="avatar" className="border-4 border-dark-900 w-12 h-12 object-cover object-center rounded-full shadow-md inline-block" src={avatarUrl}/>
        </Link>
      </div>
    </header>
  );
}
