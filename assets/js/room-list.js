document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-carousel]").forEach((wrapper) => {
    const slides = Array.from(wrapper.querySelectorAll(".rl-slide"));
    if (!slides.length) return;

    let i = parseInt(wrapper.getAttribute("data-active") || "0", 10);
    const show = (idx) => {
      slides.forEach((s, n) => {
        if (n === idx) {
          s.classList.remove("opacity-0", "pointer-events-none");
          s.classList.add("opacity-100");
        } else {
          s.classList.remove("opacity-100");
          s.classList.add("opacity-0", "pointer-events-none");
        }
      });
      wrapper.setAttribute("data-active", String(idx));
    };

    // init
    show(i);

    const prev = wrapper.querySelector(".rl-prev");
    const next = wrapper.querySelector(".rl-next");
    prev &&
      prev.addEventListener("click", () => {
        i = (i - 1 + slides.length) % slides.length;
        show(i);
      });
    next &&
      next.addEventListener("click", () => {
        i = (i + 1) % slides.length;
        show(i);
      });
  });
});
