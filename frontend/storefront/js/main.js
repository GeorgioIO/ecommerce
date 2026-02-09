import { swapClass } from "../../admin/js/UIhelpers.js";
import {
  buildCollectionsBar,
  populateCollectionsBar,
} from "./components/collectionsBar.js";
import {
  buildHeroCard,
  showNextCard,
  showPreviousCard,
} from "./components/heroCards.js";
import { BuildMiniCartBar } from "./components/miniCartBart.js";
import {
  buildMiniWishlistBar,
  createMiniWishlistContainer,
  createViewWishlistButton,
} from "./components/miniWishlistBar.js";
import { buildSearchBar } from "./components/searchBar.js";
import { getGenres_DB } from "./services/booksServices.js";
import { createCarousel } from "./components/carousel.js";
import { addToWishlist } from "./services/wishlistServices.js";
import { handleWishlistButton } from "./components/productCard.js";

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

window.addEventListener("resize", () => {
  newArrivalsCarousel.recalc();
  bestSellersCarousel.recalc();
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
    // Create wishlist sidebar
    const miniWishlist = buildMiniWishlistBar();

    // append to body
    body.append(miniWishlist);

    const wishlistBody = miniWishlist.querySelector(".mini-wishlist-body");

    // populate it with data
    const itemsContainer = await createMiniWishlistContainer();

    // Create view wishlist button
    const viewWishlistButton = createViewWishlistButton();

    wishlistBody.append(itemsContainer, viewWishlistButton);

    miniWishlist.append(wishlistBody);
    // show it
    swapClass(miniWishlist, "slide-in-right", "slide-out-right");
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
    handleWishlistButton(productCard, wishlistButton);
  }

  if (carouselPreviousButton) {
    bestSellersCarousel.goPrev();
  }

  if (carouselNextButton) {
    bestSellersCarousel.goNext();
  }
});

newArrivals.addEventListener("click", (e) => {
  const carouselPreviousButton = e.target.closest(".carousel-button.prev");
  const carouselNextButton = e.target.closest(" .carousel-button.next");

  if (carouselPreviousButton) {
    newArrivalsCarousel.goPrev();
  }

  if (carouselNextButton) {
    newArrivalsCarousel.goNext();
  }
});

booksUnder.addEventListener("click", (e) => {
  const carouselPreviousButton = e.target.closest(".carousel-button.prev");
  const carouselNextButton = e.target.closest(" .carousel-button.next");

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
