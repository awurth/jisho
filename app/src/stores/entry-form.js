import { create } from "zustand";

export const useEntryFormStore = create((set) => ({
  visible: false,
  setVisible: (visible) => set(() => ({ visible })),
}));
