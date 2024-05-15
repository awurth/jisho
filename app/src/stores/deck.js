import { create } from "zustand";
import { persist } from "zustand/middleware";

export const useDeckStore = create(
  persist(
    (set) => ({
      activeDeck: null,
      setActiveDeck: (deck) =>
        set(() => ({ activeDeck: deck })),
    }),
    {
      name: "deck",
    },
  ),
);
