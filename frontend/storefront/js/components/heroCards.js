import { swapClass } from "../../../admin/js/UIhelpers.js";

const heroCards = {
  one: {
    Title: "Discover a huge variety of genres",
    CTA: "Explore our collections",
  },
  two: {
    Title: "Book of the month",
    CTA: "Buy Now",
  },
  three: {
    Title: "For each three books get 30% OFF",
    CTA: "Buy Now",
  },
};

export function buildHeroCard(number) {
  const heroCard = document.createElement("div");
  heroCard.classList.add("hero-card");

  const heroTextContainer = document.createElement("div");
  heroTextContainer.classList.add("hero-text-container");

  const heroTitle = document.createElement("h3");
  heroTitle.textContent = heroCards?.[number]?.Title;

  heroTextContainer.append(heroTitle);

  const CTAButton = document.createElement("a");
  CTAButton.textContent = heroCards?.[number]?.CTA;

  heroCard.append(heroTextContainer, CTAButton);

  return heroCard;
}

export function showNextCard(cards, index) {
  const current = cards[index];
  swapClass(current, "exit-left", "active");

  const nextIndex = (index + 1) % cards.length;
  const next = cards[nextIndex];
  next.classList.remove("exit-left", "exit-right");
  next.classList.add("active");
}

export function showPreviousCard(cards, index) {
  const current = cards[index];
  swapClass(current, "exit-right", "active");

  const prevIndex = (index - 1 + cards.length) % cards.length;
  const previous = cards[prevIndex];
  previous.classList.remove("exit-left", "exit-right");
  previous.classList.add("active");
}
