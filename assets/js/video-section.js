document.addEventListener("click", (e) => {
  const root = e.target.closest(".video-lite");
  if (!root || root.dataset.loaded === "1") return;

  const src = root.getAttribute("data-embed");
  if (!src) return;

  const iframe = document.createElement("iframe");
  iframe.src = src;
  iframe.title = root.getAttribute("aria-label") || "Lecteur vidéo";
  iframe.allow =
    "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
  iframe.allowFullscreen = true;
  iframe.loading = "lazy";
  iframe.referrerPolicy = "strict-origin-when-cross-origin";
  iframe.sandbox =
    "allow-same-origin allow-scripts allow-popups allow-presentation";
  iframe.className = "absolute inset-0 h-full w-full rounded-xl border-0";

  root.innerHTML = "";
  root.appendChild(iframe);
  root.dataset.loaded = "1";
});
