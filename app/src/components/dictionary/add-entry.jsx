import clsx from 'clsx';
import {useState} from 'react';
import useBodyClick from '../../hooks/useBodyClick.js';
import AddEntryForm from './add-entry-form.jsx';

export default function AddEntry({onAdd, ...props}) {
  const [newEntryFormVisible, setNewEntryFormVisible] = useState(false);

  useBodyClick('.add-entry', () => setNewEntryFormVisible(false));

  return (
    <div {...props}
         className={clsx('add-entry', props.className ?? '')}>
      <p className={clsx('text-gray-400 py-2 px-4 cursor-text', {'hidden': newEntryFormVisible})}
         onClick={() => setNewEntryFormVisible(!newEntryFormVisible)}>
        Ajouter un mot...
      </p>
      {newEntryFormVisible && <AddEntryForm onAdd={onAdd}/>}
    </div>
  );
}
