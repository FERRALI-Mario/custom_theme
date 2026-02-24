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

  // lightbox initialization using global variable exported from Twig
  const photos = window.rlLightboxPhotos || [];
  if (photos.length) {
    let current = 0;
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lb-img');
    const open = idx => {
      current = idx;
      lbImg.src = photos[idx].src;
      lbImg.alt = photos[idx].alt;
      lb.classList.remove('hidden');
    };
    document.querySelectorAll('[data-lightbox-index]').forEach(el => {
      el.addEventListener('click', e => {
        e.preventDefault();
        open(parseInt(el.dataset.lightboxIndex, 10));
      });
    });
    document.getElementById('lb-close').addEventListener('click', () => lb.classList.add('hidden'));
    document.getElementById('lb-prev').addEventListener('click', () => {
      current = (current - 1 + photos.length) % photos.length;
      open(current);
    });
    document.getElementById('lb-next').addEventListener('click', () => {
      current = (current + 1) % photos.length;
      open(current);
    });
    lb.addEventListener('click', e => {
      if (e.target === lb) lb.classList.add('hidden');
    });
  }
});

function scrollTrack(btn, direction) {
  const container = btn.closest(".relative").querySelector(".carousel-track");
  if (container) {
    const scrollAmount = container.clientWidth;
    container.scrollBy({
      left: direction * scrollAmount,
      behavior: "smooth",
    });
  }
}
