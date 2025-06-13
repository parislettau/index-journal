// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['DinSynt', 'sans-serif'],
      },
      // Adding custom utilities for scroll snap behavior
      scrollSnapType: {
        y: "y mandatory", // Enables vertical snapping
      },
      scrollSnapAlign: {
        start: "start", // Aligns elements to start on scroll
      },
    },
  },
  plugins: [
    function ({ addUtilities }) {
      addUtilities({
        ".snap-y": { scrollSnapType: "y mandatory" },
        ".snap-mandatory": { scrollSnapType: "mandatory" },
        ".snap-start": { scrollSnapAlign: "start" },
      });
    },
  ],
};
