(function () {
  const overlay = document.getElementById("pg-filters-overlay");
  if (!overlay) return;

  const panel = overlay.querySelector(".bg-white.rounded-2xl");
  const openBtn = document.getElementById("pg-open-filters");
  const closeBtn = document.getElementById("pg-close-filters");

  function open() {
    overlay.classList.remove("hidden");
    overlay.setAttribute("aria-hidden", "false");
  }
  function close() {
    overlay.classList.add("hidden");
    overlay.setAttribute("aria-hidden", "true");
  }

  openBtn?.addEventListener("click", open);
  closeBtn?.addEventListener("click", close);
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !overlay.classList.contains("hidden")) close();
  });
  // Fermer au clic hors panneau
  overlay.addEventListener("click", (e) => {
    if (panel && !panel.contains(e.target)) close();
  });

  // ---------- Catégories : single-select (checkbox UI) ----------
  const catInputs = Array.from(document.querySelectorAll("input.pg-cat"));
  catInputs.forEach((inp) => {
    inp.addEventListener("change", () => {
      if (inp.checked)
        catInputs.forEach((other) => {
          if (other !== inp) other.checked = false;
        });
    });
  });

  // ---------- Double range prix ----------
  const rMin = document.getElementById("pg-range-min");
  const rMax = document.getElementById("pg-range-max");
  const hMin = document.getElementById("pg-hidden-min");
  const hMax = document.getElementById("pg-hidden-max");
  const labMin = document.getElementById("pg-label-min");
  const labMax = document.getElementById("pg-label-max");
  const track = document.getElementById("pg-track-fill");

  function clampRange() {
    const minBase = Number(rMin?.min || 0);
    const maxBase = Number(rMax?.max || 0);
    let min = Number(rMin?.value || minBase);
    let max = Number(rMax?.value || maxBase);

    if (min > max) [min, max] = [max, min];
    min = Math.max(minBase, Math.min(min, maxBase));
    max = Math.max(min, Math.min(max, maxBase));

    if (rMin) rMin.value = min;
    if (rMax) rMax.value = max;
    if (hMin) hMin.value = min;
    if (hMax) hMax.value = max;
    if (labMin) labMin.textContent = min;
    if (labMax) labMax.textContent = max;

    const span = maxBase - minBase || 1;
    const minPct = ((min - minBase) / span) * 100;
    const maxPct = ((max - minBase) / span) * 100;
    if (track) {
      track.style.left = minPct + "%";
      track.style.right = 100 - maxPct + "%";
    }
  }
  rMin?.addEventListener("input", clampRange);
  rMax?.addEventListener("input", clampRange);
  clampRange();

  // ---------- Étoiles cliquables (FIX complet) ----------
  const starsWrap = document.getElementById("pg-stars");
  const starsInput = document.getElementById("pg-stars-input");
  const starsClear = document.getElementById("pg-stars-clear");

  function paintStars(val) {
    starsWrap?.querySelectorAll(".pg-star").forEach((btn) => {
      const n = Number(btn.dataset.val);
      btn.textContent = n <= val ? "★" : "☆";
    });
  }

  if (starsWrap && starsInput) {
    // init depuis valeur courante
    const current = Number(starsWrap.dataset.current || starsInput.value || 0);
    paintStars(current);

    starsWrap.addEventListener("click", (e) => {
      const btn = e.target.closest(".pg-star");
      if (!btn) return;
      const val = Number(btn.dataset.val);
      const cur = Number(starsInput.value || 0);
      const next = val === cur ? 0 : val; // recliquer = annule
      starsInput.value = next || "";
      paintStars(next);
    });

    starsClear?.addEventListener("click", () => {
      starsInput.value = "";
      paintStars(0);
    });
  }

  // ---------- Réinitialiser ----------
  const resetBtn = document.getElementById("pg-reset");
  const shopURL = resetBtn?.dataset.shopUrl || window.location.pathname;
  resetBtn?.addEventListener("click", () => {
    window.location.href = shopURL;
  });
})();
