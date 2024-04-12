import {faArrowRightArrowLeft} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {useEffect, useRef, useState} from 'react';
import {bind} from 'wanakana';
import Button from '../forms/button.jsx';
import Input from '../forms/input.jsx';
import existingTags from '../../data/tags.json';
import Tags from '../forms/tags.jsx';

export default function AddEntryForm({onAdd, ...props}) {
  const japaneseRef = useRef(null);
  const [french, setFrench] = useState('');
  const [tags, setTags] = useState([]);

  useEffect(() => {
    bind(japaneseRef.current);
    japaneseRef.current.focus();
  }, []);

  const onAddClick = () => {
    const kana = japaneseRef.current.value;

    onAdd && onAdd({
      japanese: kana,
      french: french.split(', '),
      tags: tags.map((tag) => tag.value),
    });

    japaneseRef.current.value = '';
    setFrench('');
    setTags([]);
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
          />
          <FontAwesomeIcon icon={faArrowRightArrowLeft} className="mx-2 text-gray-500"/>
          <Input type="text"
                 placeholder="riz, repas"
                 className="w-80"
                 value={french}
                 onChange={(e) => setFrench(e.target.value)}/>
        </div>
        <Tags options={existingTags} value={tags} onChange={setTags} className="mb-2"/>
        <Button onClick={onAddClick}>Ajouter</Button>
      </div>
    </div>
  );
}
