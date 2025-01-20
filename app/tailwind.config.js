import colors from "tailwindcss/colors";

/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {},
    colors: {
      ...colors,
      primary: {
        50: "#f0f6fe",
        100: "#dceafd",
        200: "#c2dafb",
        300: "#97c4f9",
        400: "#66a4f4",
        500: "#4382ee",
        600: "#2d64e3",
        700: "#2550d0",
        800: "#2442a9",
        900: "#223b86",
        950: "#192552",
      },
      secondary: {
        50: "#fafafa",
        100: "#f5f5f5",
        200: "#e5e5e5",
        300: "#d4d4d4",
        400: "#a3a3a3",
        500: "#737373",
        600: "#525252",
        700: "#404040",
        800: "#262626",
        900: "#171717",
        950: "#0a0a0a",
      },
      illustration: "#ffcfc1",
      dark: {
        50: "#f6f6f6",
        100: "#e7e7e7",
        200: "#d1d1d1",
        300: "#b0b0b0",
        400: "#888888",
        500: "#6d6d6d",
        600: "#5d5d5d",
        700: "#4f4f4f",
        800: "#454545",
        900: "#3d3d3d",
        950: "#1f1f1f",
      },
    },
  },
  plugins: [],
};
