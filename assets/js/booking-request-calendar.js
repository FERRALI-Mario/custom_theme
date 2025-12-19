(function () {
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const $ = (s, r = document) => r.querySelector(s);

  // 1. FORMATAGE DATE ROBUSTE
  // Force le format YYYY-MM-DD local pour correspondre exactement à PHP
  const fmt = (d) => {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${day}`;
  };

  const parse = (s) => {
    const [y, m, dd] = s.split("-").map(Number);
    return new Date(y, m - 1, dd);
  };

  const addDays = (d, n) => {
    const x = new Date(d);
    x.setDate(x.getDate() + n);
    return x;
  };

  const monthsFr = [
    "janvier",
    "février",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "décembre",
  ];

  document.addEventListener("DOMContentLoaded", () => {
    if (!window.BRC) return;
    const root = document.querySelector(".booking-request-calendar-block");
    if (!root) return;

    // Récupération des dates bloquées (PHP)
    // On s'assure que c'est un tableau de Strings pour que .has() fonctionne
    const blockedRaw = BRC.blocked || [];
    const blockedSet = new Set(blockedRaw.map(String));

    // Debug : Regardez la console (F12) pour voir si les dates s'affichent ici
    console.log("Dates indisponibles chargées :", blockedSet);

    const minStay = parseInt((BRC.rules && BRC.rules.min_stay) || 7, 10);

    let current = {
      year: (BRC.current || {}).year || new Date().getFullYear(),
      month: (BRC.current || {}).month || new Date().getMonth() + 1,
    };

    let start = null;
    let end = null;

    // Elements DOM
    const monthLabel = $(".brc-current-month", root);
    const tbody = $(".brc-body", root);
    const feedback = $("#brc-feedback", root);
    const openBtn = $("#brc-open-modal", root);
    const modal = $("#brc-modal", root);
    const form = $("#brc-form", modal);
    const closeBtn = $(".brc-close", modal);
    const formError = $("#brc-form-error", form);
    const inDisplay = $("#brc-checkin-input", root);
    const outDisplay = $("#brc-checkout-input", root);
    const inHidden = $('input[name="checkin"]', form);
    const outHidden = $('input[name="checkout"]', form);

    function renderMonth() {
      const ml = monthsFr[current.month - 1];
      monthLabel.textContent = `${ml.charAt(0).toUpperCase() + ml.slice(1)} ${
        current.year
      }`;

      // --- CALCUL DYNAMIQUE DE LA GRILLE ---
      const firstOfMonth = new Date(current.year, current.month - 1, 1);
      const daysInMonth = new Date(current.year, current.month, 0).getDate();

      const dow = firstOfMonth.getDay(); // 0 = Dimanche
      const offset = (dow + 6) % 7; // Décalage pour commencer Lundi
      const startGrid = addDays(firstOfMonth, -offset);

      // Calcul précis du nombre de cases nécessaires (multiple de 7)
      // Cela évite d'avoir une ligne vide en bas
      const totalCells = Math.ceil((offset + daysInMonth) / 7) * 7;

      let html = "";

      for (let i = 0; i < totalCells; i++) {
        // Début de ligne (Lundi)
        if (i % 7 === 0) html += "<tr>";

        const d = addDays(startGrid, i);
        const dateStr = fmt(d);
        const inMonth = d.getMonth() + 1 === current.month;
        const isBlocked = blockedSet.has(dateStr);

        // --- STYLES CSS (Flat Design) ---
        // Base : rond, centré, pas d'ombre
        let classes =
          "brc-day w-10 h-10 mx-auto inline-flex items-center justify-center rounded-full text-sm transition border border-transparent ";

        if (isBlocked) {
          // INDISPONIBLE : Gris foncé + Barré + Curseur interdit
          classes +=
            "bg-gray-200 text-gray-400 line-through cursor-not-allowed decoration-gray-400";
        } else if (inMonth) {
          // MOIS COURANT : Blanc
          classes += "bg-white hover:bg-gray-100 text-gray-900 cursor-pointer";
        } else {
          // HORS MOIS : Gris clair MAIS Sélectionnable (cursor-pointer)
          classes += "text-gray-400 hover:bg-gray-50 cursor-pointer";
        }

        html += `<td class="px-1 py-1 align-middle text-center">
          <button type="button" 
            class="${classes}"
            data-date="${dateStr}"
            data-blocked="${isBlocked ? 1 : 0}">
            ${d.getDate()}
          </button>
        </td>`;

        // Fin de ligne (Dimanche)
        if ((i + 1) % 7 === 0) html += "</tr>";
      }

      tbody.innerHTML = html;

      $$(".brc-day", tbody).forEach((b) =>
        b.addEventListener("click", onDayClick)
      );
      highlightSelection();
    }

    function highlightSelection() {
      $$(".brc-day", tbody).forEach((el) => {
        // On ne touche pas au style des dates indisponibles
        if (el.dataset.blocked === "1") return;

        // 1. RESET : On retire tous les styles d'état
        el.classList.remove(
          "bg-gray-900",
          "text-white",
          "bg-gray-50",
          "hover:bg-gray-800",
          "scale-105"
        );

        // 2. ÉTAT PAR DÉFAUT
        const d = parse(el.dataset.date);
        const inMonth = d.getMonth() + 1 === current.month;

        if (inMonth) {
          el.classList.add("bg-white", "text-gray-900", "hover:bg-gray-100");
          el.classList.remove("text-gray-400"); // Sécurité
        } else {
          el.classList.add("text-gray-400"); // Gris pour hors mois
          el.classList.remove("bg-white", "text-gray-900");
        }
      });

      // 3. APPLIQUER LA SÉLECTION (Prioritaire)
      if (start) {
        const sEl = $(`button[data-date="${start}"]`, tbody);
        if (sEl) {
          // Style DÉBUT : Noir (supprime le gris ou le blanc)
          sEl.classList.remove(
            "bg-white",
            "text-gray-900",
            "hover:bg-gray-100",
            "text-gray-400"
          );
          sEl.classList.add(
            "bg-gray-900",
            "text-white",
            "hover:bg-gray-800",
            "scale-105"
          );
        }
      }

      if (end) {
        const eEl = $(`button[data-date="${end}"]`, tbody);
        if (eEl) {
          // Style FIN : Noir
          eEl.classList.remove(
            "bg-white",
            "text-gray-900",
            "hover:bg-gray-100",
            "text-gray-400"
          );
          eEl.classList.add(
            "bg-gray-900",
            "text-white",
            "hover:bg-gray-800",
            "scale-105"
          );
        }

        // 4. INTERVALLE
        const sd = parse(start);
        const ed = parse(end);

        $$(".brc-day", tbody).forEach((el) => {
          if (el.dataset.blocked === "1") return;

          const d = parse(el.dataset.date);
          if (d > sd && d < ed) {
            // Entre les deux : Gris clair
            el.classList.remove("bg-white", "text-gray-400", "text-gray-900");
            el.classList.add("bg-gray-100", "text-gray-900");
          }
        });
      }
    }

    // ... (Helpers UI identiques) ...
    function showFeedback(msg, type = "error") {
      feedback.classList.remove("hidden", "text-red-600", "text-green-600");
      feedback.classList.add(
        type === "error" ? "text-red-600" : "text-green-600"
      );
      feedback.innerHTML = msg;
    }

    function hideFeedback() {
      feedback.classList.add("hidden");
      feedback.innerHTML = "";
    }

    function onDayClick(e) {
      const btn = e.currentTarget;
      // Empêcher le clic sur une date bloquée
      if (btn.dataset.blocked === "1") return;

      const date = btn.dataset.date;
      hideFeedback();

      if (!start || (start && end)) {
        start = date;
        end = null;
        inDisplay.value = date.split("-").reverse().join("/");
        outDisplay.value = "";
        openBtn.classList.add("hidden");
      } else {
        let d = parse(date);
        let s = parse(start);

        if (d < s) {
          start = date;
          inDisplay.value = date.split("-").reverse().join("/");
        } else if (d.getTime() === s.getTime()) {
          start = null;
          inDisplay.value = "";
        } else {
          // Validation Disponibilité
          let isValid = true;
          let temp = new Date(s);
          while (temp <= d) {
            if (blockedSet.has(fmt(temp))) {
              isValid = false;
              break;
            }
            temp.setDate(temp.getDate() + 1);
          }

          if (!isValid) {
            showFeedback(
              "La sélection contient des dates indisponibles.",
              "error"
            );
            end = null;
          } else {
            const diffTime = Math.abs(d - s);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < minStay) {
              end = date; // On montre visuellement la sélection
              outDisplay.value = date.split("-").reverse().join("/");
              highlightSelection(); // On force le highlight pour le feedback visuel
              showFeedback(
                `Le séjour doit être d'au moins ${minStay} nuits.`,
                "error"
              );
              openBtn.classList.add("hidden");
              return;
            }

            end = date;
            outDisplay.value = date.split("-").reverse().join("/");
            inHidden.value = start;
            outHidden.value = end;
            openBtn.classList.remove("hidden");
          }
        }
      }
      highlightSelection();
    }

    // Navigation & Events (Identiques)
    $(".brc-prev", root).addEventListener("click", () => {
      current.month--;
      if (current.month < 1) {
        current.month = 12;
        current.year--;
      }
      renderMonth();
    });
    $(".brc-next", root).addEventListener("click", () => {
      current.month++;
      if (current.month > 12) {
        current.month = 1;
        current.year++;
      }
      renderMonth();
    });
    openBtn.addEventListener("click", () => {
      formError.classList.add("hidden");
      modal.classList.remove("hidden");
    });
    const closeModal = () => modal.classList.add("hidden");
    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = "Envoi en cours...";
      formError.classList.add("hidden");
      const formData = new FormData(form);
      formData.append("action", "booking_request_submit");
      fetch(BRC.ajaxurl, { method: "POST", body: formData })
        .then((r) => r.json())
        .then((res) => {
          if (res.success) {
            closeModal();
            start = null;
            end = null;
            inDisplay.value = "";
            outDisplay.value = "";
            highlightSelection();
            openBtn.classList.add("hidden");
            showFeedback(res.data.message, "success");
            form.reset();
          } else {
            formError.innerText =
              res.data.message || "Une erreur est survenue.";
            formError.classList.remove("hidden");
          }
        })
        .catch((err) => {
          formError.innerText = "Erreur de connexion serveur.";
          formError.classList.remove("hidden");
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerText = originalText;
        });
    });

    renderMonth();
  });
})();
