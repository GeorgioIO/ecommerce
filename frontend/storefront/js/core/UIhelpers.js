import { handleWishlistButton } from "../components/productCard.js";
import { updateMiniWishlistBody } from "../components/miniWishlistBar.js";
import { loadWishlist } from "../pages/wishlistUI.js";
import { handleCartButton } from "../components/productCard.js";
import { updateCart } from "../components/miniCartBar.js";
import { calculateCartTotal } from "../components/miniCartBar.js";

export function updateExistingBarsButton(productid, buttonUpdated) {
  // Get bars
  const searchBar = document.querySelector("#search-bar") ?? null;
  const wishlistBar = document.querySelector("#mini-wishlist-bar") ?? null;

  if (buttonUpdated === "wishlist") {
    // Get search card
    if (!searchBar) return;
    const cards = searchBar.querySelectorAll(
      `.sidebar-product-card[data-productid='${productid}']`,
    );

    if (!cards) return;

    cards.forEach((card) => {
      // Get destined button
      const destinedButton = card.querySelector(
        ".product-card-add-wishlist-button",
      );

      const svg = destinedButton.querySelector("svg");
      if (destinedButton.dataset.state === "active") {
        destinedButton.dataset.state = "inactive";
        svg.setAttribute("fill", "none");
      } else {
        destinedButton.dataset.state = "active";
        svg.setAttribute("fill", "black");
      }
    });
  } else if (buttonUpdated === "cart") {
    if (!searchBar && !wishlistBar) return;
    console.log(searchBar, wishlistBar);
    const searchCard = searchBar?.querySelector(
      `.sidebar-product-card[data-productid='${productid}']`,
    );

    const wishlistCard = wishlistBar?.querySelector(
      `.sidebar-product-card[data-productid='${productid}']`,
    );

    const cards = [searchCard, wishlistCard].filter(Boolean);

    if (!cards) return;

    console.log(cards);

    cards.forEach((card) => {
      const destinedButton = card.querySelector(
        ".product-card-add-cart-button",
      );

      const svg = destinedButton.querySelector("svg");
      if (destinedButton.dataset.state === "active") {
        destinedButton.dataset.state = "inactive";
        svg.setAttribute("fill", "none");
      } else {
        destinedButton.dataset.state = "active";
        svg.setAttribute("fill", "black");
      }
    });
  }
}
export function createOutOfStockButton(className) {
  const outOfStockButton = document.createElement("button");
  outOfStockButton.classList.add(className);
  outOfStockButton.innerHTML = "Out of stock";

  return outOfStockButton;
}

export function createCartButton(card, className, type = null, state, svgFill) {
  const cartButton = document.createElement("button");
  cartButton.dataset.state = state;
  cartButton.classList.add(className);
  cartButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${svgFill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
      </svg>
  `;

  cartButton.onclick = async () => {
    await handleCartButton(card, cartButton);
    // await removeFromCart_DB(product.book_id);
    await updateCart();
    const total = await calculateCartTotal();
    const priceElement = document.querySelector(".mini-cart-price") ?? null;
    console.log("here", priceElement);
    if (priceElement) priceElement.textContent = `$${total.toFixed(2)} USD`;
  };

  return cartButton;
}

export function createWishlistButton(card, className, type, state, svgFill) {
  const wishlistButton = document.createElement("button");
  wishlistButton.classList.add(className);
  wishlistButton.dataset.enabled = "true";
  wishlistButton.dataset.state = state;
  wishlistButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${svgFill}" viewBox="0 0 24 24">
          <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
      </svg>
    `;

  wishlistButton.onclick = async () => {
    // From wishlist
    if (type === "wishlist" || type === "search") {
      await handleWishlistButton(card, wishlistButton);
      await updateMiniWishlistBody();
      if (window.location.pathname.includes("wishlist.php")) {
        await loadWishlist();
      }
    }
  };

  return wishlistButton;
}
