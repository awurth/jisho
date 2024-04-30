import axios from 'axios';
import {useEffect, useState} from 'react';
import AddEntry from '../components/dictionary/add-entry.jsx';
import Entry from '../components/dictionary/entry.jsx';
import {useDictionaryStore} from '../stores/dictionary.js';

export default function Dictionary() {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const [entries, setEntries] = useState([]);

  const [hasNewEntries, setHasNewEntries] = useState(false);

  useEffect(() => {
    axios.get(`/api/dictionaries/${dictionary.id}/entries`).then(({data}) => {
      setEntries(data);
    });
    setHasNewEntries(false);
  }, [hasNewEntries]);

  const onAdd = (entry) => {
    setHasNewEntries(true);
  };

  return (
    <>
      {/*<AddEntry onAdd={onAdd}/>*/}
      {/*<h1 className="text-xl font-semibold px-4">Dictionnaire</h1>*/}
      <div className="flex flex-col">
        {entries.map((entry) => (
          <Entry key={entry.japanese} entry={entry} className="mb-4"/>
        ))}
      </div>
    </>
  );
}
