import axios from 'axios';
import {useEffect, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import Button from '../components/forms/button.jsx';
import Tags from '../components/forms/tags.jsx';
import {useDictionaryStore} from '../stores/dictionary.js';

export default function QuizForm() {
  const navigate = useNavigate();
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const [existingTags, setExistingTags] = useState([]);
  const [tags, setTags] = useState([]);

  useEffect(() => {
    axios.get(`/api/dictionaries/${dictionary.id}/tags`).then(({data}) => {
      setExistingTags(data);
    });
  }, []);

  const onPlayClick = () => {
    const params = {
      tags: tags.map((tag) => tag.value).join(','),
    };
    navigate({
      pathname: '/quiz',
      search: new URLSearchParams(params).toString(),
    });
  };

  return (
    <>
      <h1 className="text-xl font-semibold mb-2">Nouveau quiz</h1>
      <label className="font-semibold">Tags</label>
      <Tags options={existingTags.map((tag) => tag.name)} value={tags} onChange={setTags} className="mt-1 mb-2"/>
      <Button onClick={onPlayClick}>Jouer</Button>
    </>
  );
}
