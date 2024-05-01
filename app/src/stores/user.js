import { create } from "zustand";
import { persist } from "zustand/middleware";
import { useDictionaryStore } from "./dictionary.js";

export const useUserStore = create(
  persist(
    () => ({
      user: null,
    }),
    {
      name: "user",
    },
  ),
);

useUserStore.subscribe(({ user }) => {
  if (!user) {
    useDictionaryStore.setState({ activeDictionary: null });
  }
});
