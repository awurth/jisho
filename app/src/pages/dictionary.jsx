import {useDictionaryStore} from '../stores/dictionary.js';

export default function Dictionary() {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);

  return (
    <>
      <h1 className="text-2xl">Dictionnaire {dictionary.name}</h1>
    </>
  );
}
