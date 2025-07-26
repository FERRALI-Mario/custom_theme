document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll("[data-faq-item]");

  items.forEach((item) => {
    const btn = item.querySelector("[data-faq-trigger]");
    const content = item.querySelector("[data-faq-content]");
    const icon = btn.querySelector("svg");

    btn.addEventListener("click", () => {
      const isOpen = btn.getAttribute("aria-expanded") === "true";

      // Fermer tous les autres
      items.forEach((i) => {
        const otherBtn = i.querySelector("[data-faq-trigger]");
        const otherContent = i.querySelector("[data-faq-content]");
        const otherIcon = i.querySelector("svg");

        otherBtn.setAttribute("aria-expanded", "false");
        otherContent.style.maxHeight = null;
        otherContent.setAttribute("aria-hidden", "true");
        otherIcon.classList.remove("rotate-180");
        otherIcon.classList.add("rotate-0");
      });

      // Si fermé, l'ouvrir
      if (!isOpen) {
        btn.setAttribute("aria-expanded", "true");
        content.style.maxHeight = content.scrollHeight + "px";
        content.setAttribute("aria-hidden", "false");
        icon.classList.remove("rotate-0");
        icon.classList.add("rotate-180");
      }
    });
  });
});
