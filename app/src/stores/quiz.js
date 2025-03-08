import { create } from "zustand";

export const useQuizStore = create((set) => ({
  currentQuestion: null,
  setCurrentQuestion: (question) => set({ currentQuestion: question }),
}));
