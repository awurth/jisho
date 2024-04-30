import {faUser} from '@fortawesome/free-regular-svg-icons';
import {faDumbbell, faHome, faSearch, faToriiGate} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Link} from 'react-router-dom';

export default function TabBar() {
  return (
    <footer className="fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl text-gray-400" style={{boxShadow: '0 -5px 20px 0 rgba(150,150,150,0.2)'}}>
      <ul className="grid grid-cols-4 text-center">
        <li>
          <Link to="/" className="inline-block text-gray-700 px-4 py-4">
            <FontAwesomeIcon icon={faToriiGate}/>
          </Link>
        </li>
        <li>
          <Link to="/" className="inline-block px-4 py-4">
            <FontAwesomeIcon icon={faSearch}/>
          </Link>
        </li>
        <li>
          <Link to="/new-quiz" className="inline-block px-4 py-4">
            <FontAwesomeIcon icon={faDumbbell}/>
          </Link>
        </li>
        <li>
          <Link to="/account" className="inline-block px-4 py-4">
            <FontAwesomeIcon icon={faUser}/>
          </Link>
        </li>
      </ul>
    </footer>
  );
}
