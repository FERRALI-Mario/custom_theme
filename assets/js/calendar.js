(function () {
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const $ = (s, r = document) => r.querySelector(s);

  // 1. FORMATAGE DATE ROBUSTE
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
    const closeBtnCross = $(".brc-close-button", modal);
    const formError = $("#brc-form-error", form);
    const inDisplay = $("#brc-checkin-input", root);
    const outDisplay = $("#brc-checkout-input", root);
    const inHidden = $('input[name="checkin"]', form);
    const outHidden = $('input[name="checkout"]', form);
    const priceInput = $('input[name="price"]', form);
    const topSummary = $("#brc-top-summary", root);
    const topPriceEl = $("#brc-top-price", root);

    // cleaning fee from context
    const cleaningFee =
      window.BRC_CONTEXT && !isNaN(parseFloat(window.BRC_CONTEXT.cleaning_fee))
        ? parseFloat(window.BRC_CONTEXT.cleaning_fee)
        : 0;
    // deposit percentage from context (convert 0-100 to 0-1)
    const depositPct =
      window.BRC_CONTEXT && !isNaN(parseFloat(window.BRC_CONTEXT.deposit_pct))
        ? Math.max(0, Math.min(1, parseFloat(window.BRC_CONTEXT.deposit_pct) / 100))
        : 0.4;

    function renderMonth() {
      const ml = monthsFr[current.month - 1];
      monthLabel.textContent = `${ml.charAt(0).toUpperCase() + ml.slice(1)} ${
        current.year
      }`;

      const firstOfMonth = new Date(current.year, current.month - 1, 1);
      const daysInMonth = new Date(current.year, current.month, 0).getDate();

      const dow = firstOfMonth.getDay();
      const offset = (dow + 6) % 7;
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
            <span class="text-sm font-semibold">${d.getDate()}</span>
            ${
              !isBlocked
                ? `<span class="text-white-500 font-normal leading-none -mt-0.5">${dayPrice}€</span>`
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
      const currentMD = getMonthDay(dateObj);

      for (let s of seasons) {
        const startMD = s.start_date.substring(5);
        const endMD = s.end_date.substring(5);
        if (startMD > endMD) {
          if (currentMD >= startMD || currentMD <= endMD)
            return parseFloat(s.price);
        } else {
          if (currentMD >= startMD && currentMD <= endMD)
            return parseFloat(s.price);
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
        if (el.dataset.blocked === "1") return;

        el.classList.remove(
          "bg-gray-900",
          "text-white",
          "bg-gray-50",
          "hover:bg-gray-800",
          "scale-105"
        );

        const d = parse(el.dataset.date);
        const inMonth = d.getMonth() + 1 === current.month;

        if (inMonth) {
          el.classList.add("bg-white", "text-gray-900", "hover:bg-gray-100");
          el.classList.remove("text-gray-400");
        } else {
          el.classList.add("text-gray-400");
          el.classList.remove("bg-white", "text-gray-900");
        }
      });

      if (start) {
        const sEl = $(`button[data-date="${start}"]`, tbody);
        if (sEl) {
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

        const sd = parse(start);
        const ed = parse(end);

        $$(".brc-day", tbody).forEach((el) => {
          if (el.dataset.blocked === "1") return;
          const d = parse(el.dataset.date);
          if (d > sd && d < ed) {
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
      let breakdown = {};

      while (iter < e) {
        let p = getPrice(iter);
        total += p;
        let pStr = p.toString();
        breakdown[pStr] = (breakdown[pStr] || 0) + 1;
        iter.setDate(iter.getDate() + 1);
      }

      // apply cleaning fee once per reservation
      if (cleaningFee && cleaningFee > 0) {
        total += cleaningFee;
      }

      const deposit = total * depositPct;
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
        if (cleaningFee && cleaningFee > 0) {
          const li = document.createElement("li");
          li.innerHTML = `<strong>Frais de ménage</strong> : ${cleaningFee}€`;
          listEl.appendChild(li);
        }
      }
      if ($("#brc-total")) $("#brc-total").innerText = total;
      if ($("#brc-deposit")) $("#brc-deposit").innerText = deposit.toFixed(2);
      if ($("#brc-deposit-pct")) $("#brc-deposit-pct").innerText = (depositPct * 100).toFixed(0) + "%";
      if ($("#brc-summary")) $("#brc-summary").classList.remove("hidden");

      if (topSummary && topPriceEl) {
        topPriceEl.innerText = total;
        topSummary.classList.remove("hidden");
        topSummary.classList.add("flex");
      }

      // update hidden price field so backend can use it if needed
      if (priceInput) {
        priceInput.value = total;
      }
    }

    openBtn.addEventListener("click", () => {
      updateSummary();
      formError.classList.add("hidden");
      modal.classList.remove("hidden");
    });

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
        start = date;
        end = null;
        inDisplay.value = date.split("-").reverse().join("/");
        outDisplay.value = "";
        openBtn.classList.add("hidden");
        if (topSummary) topSummary.classList.add("hidden");
      } else {
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
              if (topSummary) topSummary.classList.add("hidden");
              return;
            }
            end = date;
            outDisplay.value = date.split("-").reverse().join("/");
            inHidden.value = start;
            outHidden.value = end;
            openBtn.classList.remove("hidden");
            updateSummary();
          }
        }
      }
      highlightSelection();
    }

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

    const closeModal = () => modal.classList.add("hidden");
    if (closeBtn) closeBtn.addEventListener("click", closeModal);
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });

    if (closeBtnCross) closeBtnCross.addEventListener("click", closeModal);
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });
    
    // --- SOUMISSION DU FORMULAIRE (LOGIQUE MODIFIÉE) ---
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      // 1. Reset des erreurs
      formError.classList.add("hidden");

      // 2. VÉRIFICATION 1 : Champs vides (Priorité haute)
      let hasEmpty = false;
      // On sélectionne tous les champs requis
      const requiredInputs = $$("input[required], textarea[required]", form);

      requiredInputs.forEach((input) => {
        // Nettoyage style
        input.classList.remove("border-red-500", "bg-red-50");

        if (!input.value.trim()) {
          hasEmpty = true;
          input.classList.add("border-red-500", "bg-red-50");

          // Enlever le rouge quand on écrit
          input.addEventListener(
            "input",
            () => {
              input.classList.remove("border-red-500", "bg-red-50");
            },
            { once: true }
          );
        }
      });

      if (hasEmpty) {
        formError.innerText = "Veuillez remplir tous les champs obligatoires.";
        formError.classList.remove("hidden");
        return; // STOP : On ne vérifie même pas l'email si des champs sont vides
      }

      // 3. VÉRIFICATION 2 : Format Email (Priorité secondaire)
      const emailInput = $('input[name="email"]', form);
      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

      if (emailInput) {
        if (!emailRegex.test(emailInput.value.trim())) {
          emailInput.classList.add("border-red-500", "bg-red-50");
          formError.innerText = "L'adresse email n'est pas valide.";
          formError.classList.remove("hidden");

          emailInput.addEventListener(
            "input",
            () => {
              emailInput.classList.remove("border-red-500", "bg-red-50");
            },
            { once: true }
          );

          return; // STOP
        }
      }

      // 4. Suite du traitement (Envoi AJAX) si tout est OK
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = "Envoi en cours...";

      const formData = new FormData(form);
      // append cleaning fee and deposit percentage for transparency/back-end
      formData.append("cleaning_fee", cleaningFee);
      formData.append("deposit_pct", depositPct);
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
