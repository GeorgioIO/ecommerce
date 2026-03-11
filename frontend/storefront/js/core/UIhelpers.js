import { handleWishlistButton } from "../components/productCard.js";
import { updateMiniWishlistBody } from "../components/miniWishlistBar.js";
import { loadWishlist } from "../pages/wishlistUI.js";
import { handleCartButton } from "../components/productCard.js";
import { updateCart } from "../components/miniCartBar.js";
import { calculateCartTotal } from "../components/miniCartBar.js";
import { getCustomerAddresses_DB } from "../services/customerServices.js";
import { hydrateAddressForm } from "../components/addressForm/addressFormHydrator.js";

export function buildAddressOptionContainer() {
  const addressOptionContainer = document.createElement("div");
  addressOptionContainer.classList.add("address-option-container");

  const defaultRow = document.createElement("div");
  defaultRow.classList.add("default-address-row");

  const defaultRadio = document.createElement("input");
  defaultRadio.type = "radio";
  defaultRadio.id = "default-address-radio";
  defaultRadio.name = "address-radio";
  defaultRadio.checked = true;

  defaultRadio.onchange = async () => {
    const form = document.querySelector("#address-form");

    const customerAddress = await getCustomerAddresses_DB();
    hydrateAddressForm(form, customerAddress[0]);
  };

  const defaultLabel = document.createElement("label");
  defaultLabel.htmlFor = "default-address-radio";
  defaultLabel.textContent = "Use default address";

  defaultRow.append(defaultRadio, defaultLabel);

  const newAddressRow = document.createElement("div");
  newAddressRow.classList.add("new-address-row");

  const newAddressRadio = document.createElement("input");
  newAddressRadio.type = "radio";
  newAddressRadio.id = "new-address-radio";
  newAddressRadio.name = "address-radio";

  newAddressRadio.onchange = (e) => {
    const form = document.querySelector("#address-form");

    form.reset();
  };

  const newAddressLabel = document.createElement("label");
  newAddressLabel.htmlFor = "new-address-radio";
  newAddressLabel.textContent = "Use new address (Will not be saved)";

  newAddressRow.append(newAddressRadio, newAddressLabel);

  addressOptionContainer.append(defaultRow, newAddressRow);

  return addressOptionContainer;
}

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
