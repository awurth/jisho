import {faArrowRightArrowLeft, faArrowsUpDown} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import axios from 'axios';
import {useEffect, useRef, useState} from 'react';
import {bind, isKana} from 'wanakana';
import {useDictionaryStore} from '../../stores/dictionary.js';
import Button from '../forms/button.jsx';
import Input from '../forms/input.jsx';
import existingTags from '../../data/tags.json';
import Tags from '../forms/tags.jsx';
import Textarea from '../forms/textarea.jsx';

export default function AddEntry({onAdd, ...props}) {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const japaneseRef = useRef(null);
  const [french, setFrench] = useState('');
  const [tags, setTags] = useState([]);
  const [notes, setNotes] = useState('');

  const [japaneseError, setJapaneseError] = useState(null);
  const [frenchError, setFrenchError] = useState(null);

  const onKeyUp = (e) => {
    if (e.code === 'Enter' && !e.shiftKey) {
      validate() && submit();
    }
  };

  const validateJapanese = () => {
    const japanese = japaneseRef.current.value;
    if (japanese.length === 0 || !isKana(japanese)) {
      setJapaneseError('Seuls les hiragana et les katakana sont acceptés');
      return false;
    }

    setJapaneseError(null);
    return true;
  };

  const validateFrench = () => {
    if (french.length === 0) {
      setFrenchError('Veuillez renseigner au moins un mot');
      return false;
    }

    setFrenchError(null);
    return true;
  };

  const validate = () => {
    const validJapanese = validateJapanese();
    const validFrench = validateFrench();

    return validJapanese && validFrench;
  };

  useEffect(() => {
    bind(japaneseRef.current);
    japaneseRef.current.focus();
  }, []);

  const submit = () => {
    const kana = japaneseRef.current.value;

    const data = {
      japanese: kana,
      french: french.split(', '),
      tags: tags.map((tag) => tag.value),
    };

    axios.post(`/api/dictionaries/${dictionary.id}/entries`, data).then((response) => {
      japaneseRef.current.value = '';
      setJapaneseError(null);

      setFrench('');
      setFrenchError(null);

      setTags([]);
      setNotes('');

      onAdd(data);
    });
  };

  return (
    <div {...props}>
      <div className="flex flex-col mb-2">
        <Input type="text"
               placeholder="gohan"
               className="w-full mb-2"
               ref={japaneseRef}
               onKeyUp={onKeyUp}
               error={japaneseError}
        />
        <FontAwesomeIcon icon={faArrowsUpDown} className="text-gray-500 mb-2"/>
        <Input type="text"
               placeholder="riz, repas"
               className="w-full"
               value={french}
               onKeyUp={onKeyUp}
               onChange={(e) => setFrench(e.target.value)}
               error={frenchError}/>
      </div>
      <Tags options={existingTags} value={tags} onChange={setTags} className="mb-2"/>
      <Textarea className="w-full" placeholder="Notes" onChange={(e) => setNotes(e.target.value)} onKeyUp={onKeyUp} value={notes}/>
      <Button onClick={submit}>Ajouter</Button>
    </div>
  );
}
