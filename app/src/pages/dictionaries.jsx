import axios from 'axios';
import {useEffect, useState} from 'react';

export default function Dictionaries() {
  const [dictionaries, setDictionaries] = useState([]);

  useEffect(() => {
    axios.get('/api/dictionaries').then(({data}) => {
      setDictionaries(data);
    });
  }, []);

  return (
    <>
      <h1 className="text-2xl">Dictionnaires</h1>
      {dictionaries.length && <ul>
        {dictionaries.map(({id, name}) => (
          <div key={id} className="border-2">{name}</div>
        ))}
      </ul>}
    </>
  );
}
