import { swapClass } from "../../admin/js/UIhelpers.js";
import {
  buildCollectionsBar,
  populateCollectionsBar,
} from "./components/collectionsBar.js";
import { showNextCard, showPreviousCard } from "./components/heroCards.js";
import { BuildMiniCartBar } from "./components/miniCartBart.js";
import {
  renderMiniWishlist,
  updateMiniWishlistBody,
} from "./components/miniWishlistBar.js";
import { buildSearchBar } from "./components/searchBar.js";
import { getGenres_DB } from "./services/booksServices.js";
import { createCarousel } from "./components/carousel.js";
import { handleWishlistButton } from "./components/productCard.js";
import { getSession } from "./services/sessionServices.js";

import {
  createMesssageBox,
  appendMessageBox,
  activateMessageBox,
} from "./components/messageBox.js";

const body = document.body;
const sidebar = document.querySelector("#site-sidebar");
const header = document.querySelector("#site-header");
const hero = document.querySelector("#hero");
const newArrivals = document.querySelector("#new-arrivals");
const bestSellers = document.querySelector("#best-sellers");
const booksUnder = document.querySelector("#books-under-price");

const newArrivalsCarousel = createCarousel(newArrivals);
const bestSellersCarousel = createCarousel(bestSellers);
const booksUnderPriceCarousel = createCarousel(booksUnder);

const storeReviews = document.querySelector("#reviews");
let reviewSliderToggler = true;
let heroCardNavigationIndex = 0;

document.addEventListener("DOMContentLoaded", async () => {
  const messages = await getSession();

  if (messages["redirect-message"]) {
    activateMessageBox();
    const messageBox = createMesssageBox(messages["redirect-message"]);
    appendMessageBox(messageBox);
  }
});

window.addEventListener("resize", () => {
  if (newArrivalsCarousel) {
    newArrivalsCarousel.recalc();
  }

  if (bestSellersCarousel) {
    bestSellersCarousel.recalc();
  }

  if (booksUnderPriceCarousel) {
    booksUnderPriceCarousel.recalc();
  }
});

document.addEventListener("wishlistUpdated", (e) => {
  const id = e.detail.id;

  const cards = document.querySelectorAll(
    `.product-card[data-productid="${id}"]`,
  );

  if (!cards) return;

  cards.forEach((card) => {
    const wishlistButton = card.querySelector(
      ".product-card-add-wishlist-button",
    );
    const svg = wishlistButton.querySelector("svg");
    if (wishlistButton.dataset.state === "active") {
      wishlistButton.dataset.state = "inactive";
      svg.setAttribute("fill", "none");
    }
  });
});

sidebar.addEventListener("click", async (e) => {
  const closeSidebarButton = e.target.closest("#close-sidebar-button");
  const closeCollectionContainer = e.target.closest(".close-collection-li");
  const openCollectionContainer = e.target.closest(".sidebar-collection-li");

  if (closeSidebarButton) {
    swapClass(sidebar, "slide-out-left", "slide-in-left");
  }

  if (closeCollectionContainer) {
    const genresContainer = sidebar.querySelector(".genres-sidebar-container");

    swapClass(genresContainer, "slide-out-right", "slide-in-right");

    setTimeout(() => {
      if (genresContainer) {
        genresContainer.remove();
      }
    }, 400);
  }

  if (openCollectionContainer) {
    const genres = await getGenres_DB();

    const genresContainer = buildCollectionsBar();

    populateCollectionsBar(genresContainer, genres);

    sidebar.append(genresContainer);

    swapClass(genresContainer, "slide-in-right", "slide-out-right");
  }
});

header.addEventListener("click", async (e) => {
  const hamburgerMenu = e.target.closest(".hamburger-menu");
  const showSearchButton = e.target.closest(".show-search-sidebar-button");
  const expandGenresButton = e.target.closest(
    "#show-genres-header-submenu-button",
  );
  const openWishlistButton = e.target.closest(
    "#show-mini-wishlist-menu-button",
  );
  const openMiniCartMenuButton = e.target.closest(
    "#show-mini-cart-menu-button",
  );

  if (openMiniCartMenuButton) {
    const miniCart = BuildMiniCartBar();

    body.append(miniCart);

    swapClass(miniCart, "slide-in-right", "slide-out-right");
  }

  if (openWishlistButton) {
    await renderMiniWishlist(body);
  }

  if (expandGenresButton) {
    const genresSubmenu = header.querySelector(".header-submenu-collections");

    if (genresSubmenu.classList.contains("inactive-submenu")) {
      setTimeout(() => {
        genresSubmenu.style.display = "flex";
      }, 250);
      swapClass(genresSubmenu, "active-submenu", "inactive-submenu");
    } else {
      setTimeout(() => {
        genresSubmenu.style.display = "none";
      }, 200);
      swapClass(genresSubmenu, "inactive-submenu", "active-submenu");
    }
  }

  if (hamburgerMenu) {
    swapClass(sidebar, "slide-in-left", "slide-out-left");
  }

  if (showSearchButton) {
    const searchBar = buildSearchBar();

    body.append(searchBar);

    swapClass(searchBar, "slide-in-right", "slide-out-right");
  }
});

hero.addEventListener("click", async (e) => {
  const previousHeroCardButton = e.target.closest("#previous-hero-card-button");
  const nextHeroCardButton = e.target.closest("#next-hero-card-button");

  const cards = hero.querySelectorAll(".hero-card");
  const bars = hero.querySelectorAll(".navigation-bars-container div");

  const previousButton = hero.querySelector("#previous-hero-card-button");
  const nextButton = hero.querySelector("#next-hero-card-button");

  const lastIndex = cards.length - 1;

  // Next hero card
  if (nextHeroCardButton && heroCardNavigationIndex < lastIndex) {
    showNextCard(cards, heroCardNavigationIndex);
    bars[heroCardNavigationIndex].classList.remove("active");
    bars[heroCardNavigationIndex + 1].classList.add("active");
    heroCardNavigationIndex++;
  }

  // Previous hero card
  if (previousHeroCardButton && heroCardNavigationIndex > 0) {
    showPreviousCard(cards, heroCardNavigationIndex);
    bars[heroCardNavigationIndex].classList.remove("active");
    bars[heroCardNavigationIndex - 1].classList.add("active");
    heroCardNavigationIndex--;
  }

  if (heroCardNavigationIndex === 0) {
    swapClass(previousButton, "disabled", "enabled");
  } else {
    swapClass(previousButton, "enabled", "disabled");
  }

  if (heroCardNavigationIndex === lastIndex) {
    swapClass(nextButton, "disabled", "enabled");
  } else {
    swapClass(nextButton, "enabled", "disabled");
  }
});

bestSellers.addEventListener("click", async (e) => {
  const carouselPreviousButton = e.target.closest(".carousel-button.prev");
  const carouselNextButton = e.target.closest(".carousel-button.next");
  const wishlistButton = e.target.closest(".product-card-add-wishlist-button");

  if (wishlistButton) {
    const productCard = e.target.closest(".product-card");
    await handleWishlistButton(productCard, wishlistButton);
    await updateMiniWishlistBody();
  }

  if (carouselPreviousButton) {
    bestSellersCarousel.goPrev();
  }

  if (carouselNextButton) {
    bestSellersCarousel.goNext();
  }
});

newArrivals.addEventListener("click", async (e) => {
  const carouselPreviousButton = e.target.closest(".carousel-button.prev");
  const carouselNextButton = e.target.closest(" .carousel-button.next");
  const wishlistButton = e.target.closest(".product-card-add-wishlist-button");

  if (wishlistButton) {
    const productCard = e.target.closest(".product-card");
    await handleWishlistButton(productCard, wishlistButton);
    await updateMiniWishlistBody();
  }

  if (carouselPreviousButton) {
    newArrivalsCarousel.goPrev();
  }

  if (carouselNextButton) {
    newArrivalsCarousel.goNext();
  }
});

booksUnder.addEventListener("click", async (e) => {
  const carouselPreviousButton = e.target.closest(".carousel-button.prev");
  const carouselNextButton = e.target.closest(" .carousel-button.next");
  const wishlistButton = e.target.closest(".product-card-add-wishlist-button");

  if (wishlistButton) {
    const productCard = e.target.closest(".product-card");
    await handleWishlistButton(productCard, wishlistButton);
    await updateMiniWishlistBody();
  }

  if (carouselPreviousButton) {
    booksUnderPriceCarousel.goPrev();
  }

  if (carouselNextButton) {
    booksUnderPriceCarousel.goNext();
  }
});

storeReviews.addEventListener("click", (e) => {
  const track = storeReviews.querySelector(".review-track");
  const toggle = e.target.closest(".control-review-slider-button");

  if (toggle) {
    reviewSliderToggler = !reviewSliderToggler;
    track.style.animationPlayState = reviewSliderToggler ? "running" : "paused";
    reviewSliderToggler === true
      ? (toggle.innerHTML = `                    
          <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" fill="none" viewBox="0 0 16 16">
              <path fill="#000" d="M7 1H2v14h5V1ZM14 1H9v14h5V1Z"/>
          </svg>
        `)
      : (toggle.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="-0.5 0 8 8">
            <path fill="#000" fill-rule="evenodd" d="M0 0v8l7-4z"/>
          </svg>
        `);
  }
});
