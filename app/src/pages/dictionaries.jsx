import {faCheckCircle} from '@fortawesome/free-regular-svg-icons';
import {faPlus} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import axios from 'axios';
import {useEffect, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {useDictionaryStore} from '../stores/dictionary.js';

export default function Dictionaries() {
  const [dictionaries, setDictionaries] = useState([]);
  const activeDictionaryId = useDictionaryStore((state) => state.activeDictionary?.id);
  const setActiveDictionary = useDictionaryStore((state) => state.setActiveDictionary);
  const navigate = useNavigate();

  useEffect(() => {
    axios.get('/api/dictionaries').then(({data}) => {
      setDictionaries(data);
    });
  }, []);

  const onDictionaryClick = (dictionary) => {
    setActiveDictionary(dictionary);
    navigate('/');
  };

  return (
    <>
      <h1 className="text-2xl py-3">Dictionnaires</h1>
      <ul className="flex text-gray-500">
        {dictionaries.map(({id, name}) => (
          <li key={id}
              className="flex justify-center items-center w-48 h-48 m-2 border-2 rounded-lg hover:border-primary-400 hover:text-primary-400 cursor-pointer"
              onClick={() => onDictionaryClick({id, name})}>
            {id === activeDictionaryId && <FontAwesomeIcon icon={faCheckCircle} className="mr-2 text-green-500"/>}
            {name}
          </li>
        ))}
        <li className="flex justify-center items-center w-48 h-48 m-2 border-2 rounded-lg hover:border-primary-400 hover:text-primary-400 cursor-pointer text-6xl text-gray-400">
          <FontAwesomeIcon icon={faPlus}/>
        </li>
      </ul>
    </>
  );
}
