import axios from 'axios';
import {useEffect, useRef, useState} from 'react';
import {useSearchParams} from 'react-router-dom';
import Button from '../components/forms/button.jsx';
import Input from '../components/forms/input.jsx';
import {useDictionaryStore} from '../stores/dictionary.js';

function shuffle(array) {
  let currentIndex = array.length;

  while (currentIndex !== 0) {
    let randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]];
  }
}

export default function Quiz() {
  const [searchParams] = useSearchParams();
  const answerRef = useRef(null);
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const [entries, setEntries] = useState([]);
  const [currentEntryIndex, setCurrentEntryIndex] = useState(null);
  const [points, setPoints] = useState(0);
  const [answer, setAnswer] = useState('');

  const tags = searchParams.get('tags')?.split(',').filter(Boolean);

  useEffect(() => {
    axios.get(`/api/dictionaries/${dictionary.id}/entries`).then(({data}) => {
      if (data.length === 0) {
        return;
      }

      const entries = tags.length > 0 ? data.filter((entry) => tags.some((tag) => entry.tags.includes(tag))) : data;
      shuffle(entries);
      setEntries(entries);
      setCurrentEntryIndex(0);
    });
  }, []);

  const onKeyUp = (e) => {
    if (e.code !== 'Enter') {
      return;
    }

    const entry = entries[currentEntryIndex];

    if (!entry.french.includes(e.target.value)) {
      return;
    }

    setPoints(points + 1);
    setCurrentEntryIndex(currentEntryIndex + 1);
    setAnswer('');
  };

  const skip = () => {
    setCurrentEntryIndex(currentEntryIndex + 1);
    setAnswer('');
    answerRef.current.focus();
  };

  return (
    <div className="flex flex-col h-full">
      <p className="text-sm text-primary-400 pl-4 mb-3">{dictionary.name}</p>
      <h1 className="text-xl font-semibold mb-2">Quiz "{tags.join(', ')}"</h1>
      {currentEntryIndex === entries.length && <p className="grow flex justify-center items-center font-bold text-4xl mb-32">Terminé ! {points} points / {entries.length}</p>}
      {currentEntryIndex !== entries.length && <div className="grow grid grid-cols-2">
        <div className="flex items-center justify-center p-5">
          <p className="text-4xl mb-32">{entries[currentEntryIndex]?.japanese}</p>
        </div>
        <div className="flex items-center p-5">
          <div className="mb-32 grow flex">
            <Input ref={answerRef} className="px-5 w-full h-20 text-4xl mr-1" value={answer} onChange={(e) => setAnswer(e.target.value)} onKeyUp={onKeyUp} autoFocus/>
            <Button className="px-5" onClick={() => skip()}>Passer</Button>
          </div>
        </div>
      </div>}
    </div>
  );
}
