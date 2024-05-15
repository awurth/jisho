import {useMutation, useQueryClient} from '@tanstack/react-query';
import {useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {postDeck} from '../api/deck.js';
import Button from '../components/button.jsx';
import Input from '../components/forms/input.jsx';
import {useDeckStore} from '../stores/deck.js';

export default function NewDeck() {
  const navigate = useNavigate();
  const deck = useDeckStore((state) => state.activeDeck);
  const [name, setName] = useState('');

  const queryClient = useQueryClient();
  const mutation = useMutation({
    mutationFn: (deck) => postDeck(deck),
    onSuccess: () => {
      navigate('/');
    }
  });

  const onSubmit = () => {
    mutation.mutate({name});
  };

  return (
    <>
      <h1 className="text-xl text-white font-semibold mb-2">Nouveau jeu de cartes</h1>
      <div className="flex flex-col mb-3">
        <label className="text-white font-semibold mb-1">Nom</label>
        <Input value={name} onChange={(e) => setName(e.target.value)}/>
      </div>
      <Button onClick={onSubmit} className="py-2 w-full">Créer</Button>
    </>
  );
}
