particlesJS("particles-js", {
    particles: {
      number: { value: 80, density: { enable: true, value_area: 800 } },
      color: { value: "#00BFFF" },
      shape: { type: "circle" },
      opacity: { value: 0.5 },
      size: { value: 3 },
      line_linked: { enable: true, distance: 150, color: "#00BFFF", opacity: 0.4, width: 1 },
      move: { enable: true, speed: 2 }
    },
    interactivity: {
      events: { onhover: { enable: true, mode: "repulse" } },
      modes: { repulse: { distance: 100 } }
    },
    retina_detect: true
  });