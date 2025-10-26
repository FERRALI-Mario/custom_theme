(function () {
  const prefersReduced = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;
  const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);
  const format = (n) => new Intl.NumberFormat().format(n);

  const els = document.querySelectorAll(".number-counter-block [data-count]");
  if (!els.length) return;

  const animate = (el) => {
    const target = parseFloat(el.getAttribute("data-count")) || 0;
    const duration = parseInt(el.getAttribute("data-duration") || 1200, 10);

    if (prefersReduced) {
      el.textContent = format(target);
      return;
    }

    const start = performance.now();
    const step = (now) => {
      const p = Math.min(1, (now - start) / duration);
      const val = Math.round(easeOutCubic(p) * target);
      el.textContent = format(val);
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };

  const io = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animate(entry.target);
          obs.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.4 }
  );

  els.forEach((el) => io.observe(el));
})();
