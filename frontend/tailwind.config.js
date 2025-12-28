/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,html}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#3b82f6', // Example blue
        secondary: '#10b981', // Example green
      },
    },
  },
  plugins: [],
}
