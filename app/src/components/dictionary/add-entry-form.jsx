import {faArrowRightArrowLeft} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {useEffect, useRef, useState} from 'react';
import {bind, isKana} from 'wanakana';
import Button from '../forms/button.jsx';
import Input from '../forms/input.jsx';
import existingTags from '../../data/tags.json';
import Tags from '../forms/tags.jsx';
import Textarea from '../forms/textarea.jsx';

export default function AddEntryForm({onAdd, ...props}) {
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

    setFrenchError(null)
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

    onAdd && onAdd({
      japanese: kana,
      french: french.split(', '),
      tags: tags.map((tag) => tag.value),
    });

    japaneseRef.current.value = '';
    setJapaneseError(null);

    setFrench('');
    setFrenchError(null);

    setTags([]);
    setNotes('');
  };

  return (
    <div {...props}>
      <div className="inline-block">
        {/*<button role="button" className="border-2 border-r-0 rounded-l-xl px-3 py-2 text-gray-400">あ</button>*/}
        <div className="flex items-center mb-2">
          <Input type="text"
                 placeholder="ご飯"
                 className="w-80"
                 ref={japaneseRef}
                 onKeyUp={onKeyUp}
                 error={japaneseError}
          />
          <FontAwesomeIcon icon={faArrowRightArrowLeft} className="mx-2 text-gray-500"/>
          <Input type="text"
                 placeholder="riz, repas"
                 className="w-80"
                 value={french}
                 onKeyUp={onKeyUp}
                 onChange={(e) => setFrench(e.target.value)}
                 error={frenchError}/>
        </div>
        <Tags options={existingTags} value={tags} onChange={setTags} className="mb-2"/>
        <Textarea className="w-full" placeholder="Notes" onChange={(e) => setNotes(e.target.value)} onKeyUp={onKeyUp} value={notes}/>
        <Button onClick={submit}>Ajouter</Button>
      </div>
    </div>
  );
}
