(function () {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  document.addEventListener("DOMContentLoaded", () => {
    if (!window.CONTACT_FORM) return;

    const root = $("#custom-contact-form");
    if (!root) return;

    const submitBtn = $('button[type="submit"]', root);
    const messageBox = $("#form-message", root);
    const btnText = $(".btn-text", submitBtn);
    const btnLoading = $(".btn-loading", submitBtn);
    const originalText = btnText ? btnText.innerText : "Envoyer";

    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    function showFeedback(msg, type = "error") {
      if (!messageBox) return;
      messageBox.classList.remove(
        "hidden",
        "text-red-600",
        "text-green-600",
      );

      if (type === "error") {
        messageBox.classList.add("text-red-600");
      } else {
        messageBox.classList.add("text-green-600");
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

    function validateForm() {
      let errorMsg = null;
      const inputs = $$("input, textarea", root);

      inputs.forEach((input) => {
        input.classList.remove("border-red-500"); // Reset
        const val = input.value.trim();

        if (input.hasAttribute("required") && !val) {
          input.classList.add("border-red-500");
          if (!errorMsg)
            errorMsg = "Veuillez remplir tous les champs obligatoires.";
        } else if (val && input.type === "email" && !emailRegex.test(val)) {
          input.classList.add("border-red-500");
          errorMsg = "L'adresse email n'est pas valide.";
        }

        input.addEventListener(
          "input",
          () => {
            input.classList.remove("border-red-500");
          },
          { once: true }
        );
      });

      return errorMsg; // Renvoie null si tout est bon, sinon le message
    }

    root.addEventListener("submit", (e) => {
      e.preventDefault();
      hideFeedback();

      const error = validateForm();
      if (error) {
        showFeedback(error, "error");
        return;
      }

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
