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
      <ul>
        {dictionaries.map(({id, name}) => (
          <li key={id}
              className="border-2 rounded"
              onClick={() => onDictionaryClick({id, name})}>{name}</li>
        ))}
        <li className="border-2 rounded">Nouveau</li>
      </ul>
    </>
  );
}
