document.addEventListener("DOMContentLoaded", () => {
  const timeline = document.querySelector(".timeline-block");
  const timelineItems = document.querySelectorAll(".timeline-item");
  const progressBar = document.querySelector(".timeline-progress");

  let lastVisibleIndex = -1;

  const updateProgress = (index) => {
    const ratio = (index + 1) / timelineItems.length;
    progressBar.style.transform = `scaleY(${ratio})`;
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const item = entry.target;
        const card = item.querySelector(".timeline-card");
        const dot = item.querySelector(".timeline-dot");

        if (entry.isIntersecting) {
          const index = [...timelineItems].indexOf(item);
          if (index > lastVisibleIndex) {
            lastVisibleIndex = index;
            updateProgress(index);
          }

          card.classList.add("opacity-100", "translate-y-0");
          card.classList.remove("translate-y-4");
          dot.classList.replace("bg-gray-300", "bg-black");
        }
      });
    },
    {
      threshold: 0.5,
      rootMargin: "-33% 0px -33% 0px",
    }
  );

  // ✅ Reset observer : quand la section entière sort du viewport par le haut
  const resetObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting && entry.boundingClientRect.top > 0) {
          // Scrolled up past the block
          lastVisibleIndex = -1;
          progressBar.style.transform = `scaleY(0)`;

          timelineItems.forEach((item) => {
            const card = item.querySelector(".timeline-card");
            const dot = item.querySelector(".timeline-dot");

            card.classList.remove("opacity-100", "translate-y-0");
            card.classList.add("translate-y-4");
            dot.classList.replace("bg-black", "bg-gray-300");
          });
        }
      });
    },
    {
      root: null,
      threshold: 0,
    }
  );

  // Activation des observers
  timelineItems.forEach((item) => observer.observe(item));
  if (timeline) resetObserver.observe(timeline);
});
