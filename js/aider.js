document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector(".section-partenaires__carousel .carousel-track");

  if (!track) return;

  // Const pour dupliquer les logos et créer un défilement infini
  const logos = Array.from(track.children);
  logos.forEach(logo => {
    const clone = logo.cloneNode(true);
    track.appendChild(clone);
  });
});