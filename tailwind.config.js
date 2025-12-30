/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}",
    "./views/**/*.{html,js,php}",
    "./public/**/*.{html,js,php}"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0fdfa',
          100: '#ccfbf1',
          500: '#14b8a6', // Teal-500
          600: '#0d9488', // Teal-600
          700: '#0f766e', // Teal-700
        },
        secondary: '#f43f5e', // Rose
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Keep Inter
      },
      backdropBlur: {
        xs: '2px', // Keep
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
        'fade-in': 'fadeIn 1s ease-out forwards',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
