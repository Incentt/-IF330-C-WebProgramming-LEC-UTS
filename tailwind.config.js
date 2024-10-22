/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{php,js}",        // Includes all .php and .js files in the src directory
    "./src/user/**/*.{php,js}",   // Includes all .php and .js files in the src/user directory
    "./src/admin/**/*.{php,js}",  // Includes all .php and .js files in the src/admin directory
  ],

  theme: {
    extend: {
      colors: {
        primary: '#4b9ebe',    //BIRU UTAMA
        secondary: '#6dc863',  //HIJAU
        dark: '#0c162a',       //BACKGROUND GELAP
        danger: '#FE8585',     //MERAH WARNING
        success: '#38a169',    //Optional
        light: '#b7b7b7',
        'light-10': 'rgba(183, 183, 183, 0.1)', // Light color with 50% opacity
        //Putih, text
        info: '#f3bc4f',      //KUNING
      },
      boxShadow: {
        'glowy': '0 0 4px rgba(255, 255, 255, 0.3)', // Custom white glow
      },
      backgroundImage: {
        'gradient': 'linear-gradient(to right, theme(colors.primary), theme(colors.secondary))',
      },
      fontFamily: {
        sans: ['Inter', 'Helvetica', 'Arial', 'sans-serif'], // Custom font
        serif: ['Merriweather', 'serif'],  // You can add more font families
      },
    },
  },
  plugins: [],
}
