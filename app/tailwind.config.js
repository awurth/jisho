const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
    colors: {
      ...colors,
      primary: {
        "50": "#eff1fe",
        "100": "#e1e6fe",
        "200": "#cad1fb",
        "300": "#a9b2f8",
        "400": "#8789f2",
        "500": "#706aea",
        "600": "#6a5ae0",
        "700": "#513fc3",
        "800": "#43359e",
        "900": "#3a327d",
        "950": "#231d49",
      },
      illustration: "#ffcfc1"
    }
  },
  plugins: [],
}
