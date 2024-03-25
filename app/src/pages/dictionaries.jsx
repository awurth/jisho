import axios from 'axios';
import {useEffect, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {useDictionaryStore} from '../stores/dictionary.js';

export default function Dictionaries() {
  const [dictionaries, setDictionaries] = useState([]);
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
      <h1 className="text-2xl">Dictionnaires</h1>
      <ul className="flex">
        {dictionaries.map(({id, name}) => (
          <li key={id}
              className="flex justify-center items-center w-48 h-48 m-2 border-2 rounded-lg cursor-pointer"
              onClick={() => onDictionaryClick({id, name})}>{name}</li>
        ))}
        <li className="flex justify-center items-center w-48 h-48 m-2 border-2 rounded-lg cursor-pointer">Nouveau</li>
      </ul>
    </>
  );
}
