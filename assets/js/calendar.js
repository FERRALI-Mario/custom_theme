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
    const root = document.querySelector(".calendar-block");
    if (!root) return;

    const blockedRaw = BRC.blocked || [];
    const blockedSet = new Set(blockedRaw.map(String));

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
    const priceInput = $('input[name="price"]', form);
    const pricePerNight = parseFloat(priceInput ? priceInput.value : 0);
    const topSummary = $("#brc-top-summary", root);
    const topPriceEl = $("#brc-top-price", root);

    function renderMonth() {
      const ml = monthsFr[current.month - 1];
      monthLabel.textContent = `${ml.charAt(0).toUpperCase() + ml.slice(1)} ${
        current.year
      }`;

      const firstOfMonth = new Date(current.year, current.month - 1, 1);
      const daysInMonth = new Date(current.year, current.month, 0).getDate();

      const dow = firstOfMonth.getDay(); // 0 = Dimanche
      const offset = (dow + 6) % 7; // Décalage pour commencer Lundi
      const startGrid = addDays(firstOfMonth, -offset);

      const totalCells = Math.ceil((offset + daysInMonth) / 7) * 7;

      let html = "";

      for (let i = 0; i < totalCells; i++) {
        if (i % 7 === 0) html += "<tr>";

        const d = addDays(startGrid, i);
        const dateStr = fmt(d);
        const inMonth = d.getMonth() + 1 === current.month;

        const techBlocked = blockedSet.has(dateStr);
        const priceBlocked = isPriceMissing(d);
        const isBlocked = techBlocked || priceBlocked;

        const dayPrice = getPrice(d);

        let classes =
          "brc-day w-10 h-10 mx-auto flex flex-col items-center justify-center rounded-full text-sm transition border border-transparent leading-none ";

        if (isBlocked) {
          classes +=
            "bg-gray-200 text-gray-400 cursor-not-allowed decoration-gray-400";
        } else if (inMonth) {
          classes += "bg-white hover:bg-gray-100 text-gray-900 cursor-pointer";
        } else {
          classes += "text-gray-400 hover:bg-gray-50 cursor-pointer";
        }

        html += `<td class="px-1 py-1 align-middle text-center">
          <button type="button" 
            class="${classes}"
            data-date="${dateStr}"
            data-blocked="${isBlocked ? 1 : 0}">
            
            <span class="text-sm font-semibold">
                ${d.getDate()}
            </span>

            ${
              !isBlocked
                ? `<span class="text-[8px] text-gray-500 font-normal leading-none -mt-0.5">${dayPrice}€</span>`
                : ""
            }
          </button>
        </td>`;

        if ((i + 1) % 7 === 0) html += "</tr>";
      }

      tbody.innerHTML = html;

      $$(".brc-day", tbody).forEach((b) =>
        b.addEventListener("click", onDayClick)
      );
      highlightSelection();
    }

    const getMonthDay = (d) => {
      const m = String(d.getMonth() + 1).padStart(2, "0");
      const day = String(d.getDate()).padStart(2, "0");
      return `${m}-${day}`;
    };

    const getPrice = (dateObj) => {
      const def = window.BRC_CONTEXT
        ? parseFloat(window.BRC_CONTEXT.defaultPrice)
        : 0;
      const seasons =
        window.BRC_CONTEXT && window.BRC_CONTEXT.seasonal
          ? window.BRC_CONTEXT.seasonal
          : [];

      const currentMD = getMonthDay(dateObj); // Ex: "12-25"

      for (let s of seasons) {
        // On extrait juste MM-DD des champs ACF (format YYYY-MM-DD)
        const startMD = s.start_date.substring(5); // "2025-07-01" -> "07-01"
        const endMD = s.end_date.substring(5); // "2025-08-31" -> "08-31"

        // Gestion du chevauchement d'année (ex: du 15 Déc au 15 Janv)
        if (startMD > endMD) {
          // Si la date est après le début OU avant la fin (Hiver)
          if (currentMD >= startMD || currentMD <= endMD) {
            return parseFloat(s.price);
          }
        } else {
          // Cas standard (ex: Juin à Août)
          if (currentMD >= startMD && currentMD <= endMD) {
            return parseFloat(s.price);
          }
        }
      }
      return def;
    };

    const isPriceMissing = (dateObj) => {
      const seasons =
        window.BRC_CONTEXT && window.BRC_CONTEXT.seasonal
          ? window.BRC_CONTEXT.seasonal
          : [];

      const currentMD = getMonthDay(dateObj);

      for (let s of seasons) {
        const startMD = s.start_date.substring(5);
        const endMD = s.end_date.substring(5);

        let inSeason = false;

        if (startMD > endMD) {
          if (currentMD >= startMD || currentMD <= endMD) inSeason = true;
        } else {
          if (currentMD >= startMD && currentMD <= endMD) inSeason = true;
        }

        if (inSeason) {
          const price = parseFloat(s.price);
          return !price || price <= 0;
        }
      }
      return true;
    };

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

    function updateSummary() {
      if (!start || !end) return;

      const s = parse(start);
      const e = parse(end);

      let total = 0;
      let iter = new Date(s);

      // Objet pour compter : { "50": 2, "40": 1 } -> 2 nuits à 50€, 1 nuit à 40€
      let breakdown = {};

      while (iter < e) {
        let p = getPrice(iter);
        total += p;

        // On compte les nuits par tarif
        let pStr = p.toString();
        breakdown[pStr] = (breakdown[pStr] || 0) + 1;

        iter.setDate(iter.getDate() + 1);
      }

      const deposit = total * 0.4;

      // Génération du HTML de la liste
      const listEl = $("#brc-price-breakdown");
      if (listEl) {
        listEl.innerHTML = "";
        for (const [price, nights] of Object.entries(breakdown)) {
          const li = document.createElement("li");
          li.innerHTML = `<strong>${nights} nuit${
            nights > 1 ? "s" : ""
          }</strong> à ${price}€`;
          listEl.appendChild(li);
        }
      }
      if ($("#brc-total")) $("#brc-total").innerText = total;
      if ($("#brc-deposit")) $("#brc-deposit").innerText = deposit.toFixed(2);
      if ($("#brc-summary")) $("#brc-summary").classList.remove("hidden");

      if (topSummary && topPriceEl) {
        topPriceEl.innerText = total; // Affiche le prix
        topSummary.classList.remove("hidden"); // Rend le bloc visible
        topSummary.classList.add("flex"); // Force le flex
      }
    }

    // Modifier l'écouteur du bouton d'ouverture
    openBtn.addEventListener("click", () => {
      updateSummary(); // <--- AJOUT ICI
      formError.classList.add("hidden");
      modal.classList.remove("hidden");
    });

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
      if (btn.dataset.blocked === "1") return;

      const date = btn.dataset.date;
      hideFeedback();

      if (!start || (start && end)) {
        // Reset sélection (1er clic ou nouveau clic après sélection complète)
        start = date;
        end = null;
        inDisplay.value = date.split("-").reverse().join("/");
        outDisplay.value = "";

        openBtn.classList.add("hidden");
        if (topSummary) topSummary.classList.add("hidden"); // On cache le total
      } else {
        // 2ème clic (Fin de séjour)
        let d = parse(date);
        let s = parse(start);

        if (d < s) {
          start = date;
          inDisplay.value = date.split("-").reverse().join("/");
        } else if (d.getTime() === s.getTime()) {
          start = null;
          inDisplay.value = "";
          if (topSummary) topSummary.classList.add("hidden");
        } else {
          // Vérif validité
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
            if (topSummary) topSummary.classList.add("hidden");
          } else {
            // Vérif durée min
            const diffTime = Math.abs(d - s);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < minStay) {
              end = date;
              outDisplay.value = date.split("-").reverse().join("/");
              highlightSelection();
              showFeedback(
                `Le séjour doit être d'au moins ${minStay} nuits.`,
                "error"
              );
              openBtn.classList.add("hidden");
              if (topSummary) topSummary.classList.add("hidden"); // Cache si invalide
              return;
            }

            // TOUT EST BON
            end = date;
            outDisplay.value = date.split("-").reverse().join("/");
            inHidden.value = start;
            outHidden.value = end;

            openBtn.classList.remove("hidden");
            updateSummary(); // <--- C'est ici que la magie opère !
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
