/**
 * Hero Slider — Splide initialisation.
 *
 * Targets every element with `.js-hero-slider`.
 * Splide options are tuned for CWV: loop mode, autoplay,
 * pagination dots, no arrows, and native lazy-load on non-first slides.
 */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    const sliders = document.querySelectorAll(".js-hero-slider");

    sliders.forEach((el) => {
      const autoplay = el.getAttribute("data-autoplay") !== "false";
      const delay = parseInt(el.getAttribute("data-delay"), 10) || 4000;
      const speed = parseInt(el.getAttribute("data-speed"), 10) || 600;

      new Splide(el, {
        type: "loop",
        autoplay,
        interval: delay,
        pauseOnHover: true,
        pauseOnFocus: true,
        speed,
        arrows: false,
        pagination: true,
        drag: true,
        perPage: 1,
        gap: 0,
      }).mount();
    });
  });
})();
