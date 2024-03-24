import {create} from 'zustand';
import {persist} from 'zustand/middleware';

export const useDictionaryStore = create(
  persist(
    (set) => ({
      activeDictionary: null,
      setActiveDictionary: (dictionary) => set(() => ({activeDictionary: dictionary})),
    }),
    {
      name: 'dictionary',
    }
  ),
);
