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
      <h1 className="text-sm text-primary-400 pl-4">{dictionary.name}</h1>
      <AddEntry className="my-3 ml-3" onAdd={onAdd}/>
      <div className="grid grid-cols-3 gap-3">
        {entries.map((entry) => (
          <Entry key={entry.japanese} entry={entry}/>
        ))}
      </div>
    </>
  );
}
