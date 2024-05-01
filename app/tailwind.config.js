const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {},
    colors: {
      ...colors,
      primary: {
        50: "#f3ffe4",
        100: "#e4ffc6",
        200: "#c9ff93",
        300: "#a4ff55",
        400: "#82f922",
        500: "#58cc02",
        600: "#48b300",
        700: "#378803",
        800: "#2e6b09",
        900: "#295a0d",
        950: "#113201",
      },
      secondary: {
        50: "#fefce8",
        100: "#fff9c2",
        200: "#ffef87",
        300: "#ffdf43",
        400: "#ffcd1e",
        500: "#efb103",
        600: "#ce8800",
        700: "#a46004",
        800: "#884a0b",
        900: "#733d10",
        950: "#431f05",
      },
      illustration: "#ffcfc1",
      dark: {
        50: "#f2f9f9",
        100: "#ddeef0",
        200: "#bfdee2",
        300: "#93c5cd",
        400: "#60a4b0",
        500: "#458995",
        600: "#3c707e",
        700: "#365c68",
        800: "#324f58",
        900: "#2d434c",
        950: "#131f24",
      },
    },
  },
  plugins: [],
};
