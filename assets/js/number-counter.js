document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll('.number-counter-block [data-count]');
  const speed = 100; // Plus bas = plus rapide

  counters.forEach(counter => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-count');
      const suffix = counter.textContent.replace(/[0-9]/g, '');
      const count = +counter.innerText.replace(/\D/g, '');

      const increment = Math.ceil(target / speed);

      if (count < target) {
        counter.innerText = count + increment + suffix;
        setTimeout(updateCount, 10);
      } else {
        counter.innerText = target + suffix;
      }
    };

    // Optionnel : déclenche au scroll
    const observer = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            updateCount();
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 1.0 }
    );

    observer.observe(counter);
  });
});
