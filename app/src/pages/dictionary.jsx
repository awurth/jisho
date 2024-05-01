import axios from 'axios';
import clsx from 'clsx';
import {useEffect, useState} from 'react';
import AddEntry from '../components/dictionary/add-entry.jsx';
import Entry from '../components/dictionary/entry.jsx';
import useBodyClick from '../hooks/useBodyClick.js';
import {useDictionaryStore} from '../stores/dictionary.js';
import {useEntryFormStore} from '../stores/entry-form.js';

export default function Dictionary() {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const entryFormVisible = useEntryFormStore((state) => state.visible);
  const setEntryFormVisible = useEntryFormStore((state) => state.setVisible);
  const [entries, setEntries] = useState([]);

  useBodyClick(['.add-entry', '.add-entry-button'], () => setEntryFormVisible(false));

  const [hasNewEntries, setHasNewEntries] = useState(false);

  useEffect(() => {
    axios.get(`/api/dictionaries/${dictionary.id}/entries`).then(({data}) => {
      setEntries(data);
    });
    setHasNewEntries(false);
  }, [hasNewEntries]);

  const onAdd = () => {
    setHasNewEntries(true);
  };

  return (
    <>
      <AddEntry onAdd={onAdd} className={clsx({'add-entry mb-4': true, hidden: !entryFormVisible})}/>
      <div className="flex flex-col">
        {entries.map((entry) => (
          <Entry key={entry.japanese} entry={entry} className="mb-4"/>
        ))}
      </div>
    </>
  );
}
