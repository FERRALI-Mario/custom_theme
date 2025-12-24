(function () {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  document.addEventListener("DOMContentLoaded", () => {
    // Vérification de sécurité
    if (!window.CONTACT_FORM) return;

    const root = $("#custom-contact-form");
    if (!root) return;

    const submitBtn = $('button[type="submit"]', root);
    const messageBox = $("#form-message", root);
    const btnText = $(".btn-text", submitBtn);
    const btnLoading = $(".btn-loading", submitBtn);
    const originalText = btnText ? btnText.innerText : "Envoyer";

    // Regex Email (Standard)
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    // --- Gestion de l'affichage (Feedback UI) ---

    function showFeedback(msg, type = "error") {
      if (!messageBox) return;
      messageBox.classList.remove(
        "hidden",
        "text-red-600",
        "bg-red-50",
        "text-green-600",
        "bg-green-50"
      );

      if (type === "error") {
        messageBox.classList.add("text-red-600", "bg-red-50");
      } else {
        messageBox.classList.add("text-green-600", "bg-green-50");
      }
      messageBox.innerHTML = msg;
    }

    function hideFeedback() {
      if (messageBox) messageBox.classList.add("hidden");
    }

    function setLoading(isLoading) {
      if (!submitBtn) return;
      submitBtn.disabled = isLoading;
      if (isLoading) {
        if (btnText) btnText.innerText = "Envoi...";
        if (btnLoading) btnLoading.classList.remove("hidden");
      } else {
        if (btnText) btnText.innerText = originalText;
        if (btnLoading) btnLoading.classList.add("hidden");
      }
    }

    // --- Validation Simplifiée ---

    function validateForm() {
      let errorMsg = null;
      const inputs = $$("input, textarea", root);

      inputs.forEach((input) => {
        input.classList.remove("border-red-500", "bg-red-50"); // Reset
        const val = input.value.trim();

        // 1. Vérif si vide (Champs requis)
        if (input.hasAttribute("required") && !val) {
          input.classList.add("border-red-500", "bg-red-50");
          if (!errorMsg)
            errorMsg = "Veuillez remplir tous les champs obligatoires.";
        }

        // 2. Vérif spécifique Email (si rempli)
        else if (val && input.type === "email" && !emailRegex.test(val)) {
          input.classList.add("border-red-500", "bg-red-50");
          // Ce message écrase le message générique car il est plus important
          errorMsg = "L'adresse email n'est pas valide.";
        }

        // Nettoyage visuel quand on écrit
        input.addEventListener(
          "input",
          () => {
            input.classList.remove("border-red-500", "bg-red-50");
          },
          { once: true }
        );
      });

      return errorMsg; // Renvoie null si tout est bon, sinon le message
    }

    // --- Soumission ---

    root.addEventListener("submit", (e) => {
      e.preventDefault();
      hideFeedback();

      // 1. Validation
      const error = validateForm();
      if (error) {
        showFeedback(error, "error");
        return;
      }

      // 2. Envoi AJAX
      setLoading(true);
      const formData = new FormData(root);
      formData.append("action", "contact_form_submit");
      formData.append("_wpnonce", window.CONTACT_FORM.nonce);

      fetch(window.CONTACT_FORM.ajaxurl, {
        method: "POST",
        body: formData,
      })
        .then((r) => r.json())
        .then((res) => {
          if (res.success) {
            showFeedback(res.data.message || "Message envoyé !", "success");
            root.reset();
          } else {
            showFeedback(res.data.message || "Erreur serveur.", "error");
          }
        })
        .catch(() => {
          showFeedback("Erreur de connexion.", "error");
        })
        .finally(() => {
          setLoading(false);
        });
    });
  });
})();
