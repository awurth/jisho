import { create } from "zustand";
import { persist } from "zustand/middleware";
import { useDeckStore } from "./deck.js";

export const useUserStore = create(
  persist(
    (set) => ({
      user: null,
      setUser: (user) => set(() => ({ user })),
    }),
    {
      name: "user",
    },
  ),
);

useUserStore.subscribe(({ user }) => {
  if (!user) {
    useDeckStore.setState({ activeDeck: null });
  }
});
