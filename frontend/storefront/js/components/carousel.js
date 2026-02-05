/*

createCarousel is a Factory function that return an object that allow the control of its created carousel

*/

export function createCarousel(root) {
  if (!root) return null;
  const track = root.querySelector(".carousel-track");
  const cards = Array.from(root.querySelectorAll(".carousel .product-card"));
  const btnPrevious = root.querySelector(".prev");
  const btnNext = root.querySelector(".next");
  const viewport = root.querySelector(".carousel-viewport");

  if (!track || cards.length === 0 || !btnPrevious || !btnNext) return null;

  let index = 0;
  let step = 0;
  let cardsPerView = 1;
  let maxIndex = 0;
  let startX = 0;
  let isDragging = false;

  viewport.addEventListener(
    "touchstart",
    (e) => {
      startX = e.touches[0].clientX;
      isDragging = true;
    },
    { passive: true },
  );

  viewport.addEventListener("touchend", (e) => {
    if (!isDragging) return;
    isDragging = false;

    const endX = e.changedTouches[0].clientX;
    const diff = endX - startX;
    const threshold = 40;

    if (diff > threshold) goPrev();
    else if (diff < -threshold) goNext();
  });

  function getGapPx() {
    // Get the gap between each product card
    const gap = getComputedStyle(track).gap;
    return parseFloat(gap) || 0;
  }

  function recalc() {
    // Get viewport width , meaning the width of what is seen
    const viewportWidth = root.querySelector(".carousel-viewport").clientWidth;

    // Get width of a single card
    const cardWidth = cards[0].getBoundingClientRect().width;

    const gap = getGapPx();

    // this get how many cards can be seen , it get what is the viewport width + gap , divide it on a card width with the gap
    cardsPerView = Math.max(
      1,
      Math.round((viewportWidth + gap) / (cardWidth + gap)),
    );

    // How much the button move in width
    step = cardWidth + gap;

    // Max index
    maxIndex = Math.max(0, cards.length - cardsPerView);

    // Index of first seeable card
    index = Math.min(index, maxIndex);

    update(false);
  }

  // Controlling the animation
  function update(animate = true) {
    track.style.animation = animate ? "transform 300ms ease" : "none";
    track.style.transform = `translateX(${-index * step}px)`;

    btnPrevious.disabled = index === 0;
    btnNext.disabled = index === maxIndex;
  }

  function goNext() {
    index = Math.min(index + 1, maxIndex);
    update();
  }

  function goPrev() {
    index = Math.max(index - 1, 0);
    update();
  }

  recalc();

  return { goNext, goPrev, recalc };
}
