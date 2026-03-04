import { swapClass } from "../../../admin/js/UIhelpers.js";
import { getCartItems } from "../services/cartServices.js";
import { buildSidebarCard } from "./sidebarProductCard.js";

export async function renderMiniCartBar(body) {
  // Get cart data
  const { data, success, status, message } = await getCartItems();
  let dataAvailable = false;
  if (data.length > 0) dataAvailable = true;

  // Create cart bar
  const miniCart = BuildMiniCartBar(dataAvailable);

  // Get cart body
  const cartBody = miniCart.querySelector(".mini-cart-body");

  body.append(miniCart);

  if (success && data.length > 0) {
    const cartItemsContainer = createCartItemsContainer(data);
    cartBody.append(cartItemsContainer);

    calculateCartTotal(data);
  }

  swapClass(miniCart, "slide-in-right", "slide-out-right");
}

export function createCartItemsContainer(data = null) {
  if (!data) return;

  const cartItemsContainer = document.createElement("div");
  cartItemsContainer.classList.add("mini-cart-items-container");

  data.forEach(async (item) => {
    cartItemsContainer.append(await buildSidebarCard(item, "cart"));
  });

  return cartItemsContainer;
}

export function BuildMiniCartBar(dataAvailable) {
  let miniCartMenu = document.querySelector("#mini-cart-bar") ?? null;

  if (miniCartMenu) {
    miniCartMenu.remove();
  }

  miniCartMenu = document.createElement("div");
  miniCartMenu.id = "mini-cart-bar";

  // Header
  const miniCartMenuHeader = document.createElement("header");
  miniCartMenuHeader.classList.add("mini-cart-bar-header");

  const miniCartMenuTitle = document.createElement("h2");
  miniCartMenuTitle.classList.add("mini-cart-bar-title");
  miniCartMenuTitle.textContent = "CART";

  const closeMiniCartMenu = document.createElement("button");
  closeMiniCartMenu.id = "close-mini-cart-button";
  closeMiniCartMenu.type = "button";
  closeMiniCartMenu.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
    </svg>
  `;

  miniCartMenuHeader.append(miniCartMenuTitle, closeMiniCartMenu);

  const cartBody = document.createElement("div");
  cartBody.classList.add("mini-cart-body");

  miniCartMenu.append(miniCartMenuHeader, cartBody);

  if (dataAvailable) {
    const cartFooter = document.createElement("div");
    cartFooter.classList.add("mini-cart-footer");

    const priceRow = document.createElement("div");
    priceRow.classList.add("mini-cart-footer-price-row");

    const total = document.createElement("p");
    total.textContent = "Total";

    const price = document.createElement("p");
    price.classList.add("mini-cart-price");
    price.textContent = "$0 USD";

    priceRow.append(total, price);

    const divider = document.createElement("div");
    divider.classList.add("divider");

    const taxShipping = document.createElement("p");
    taxShipping.style.textAlign = "center";
    taxShipping.textContent = "Taxes and shipping collected at checkout";

    const buttonsContainer = document.createElement("div");
    buttonsContainer.classList.add("mini-cart-footer-buttons-container");

    const viewCartButton = document.createElement("a");
    viewCartButton.id = "view-cart-button";
    viewCartButton.textContent = "VIEW CART";

    const checkoutButton = document.createElement("a");
    checkoutButton.id = "checkout-button";
    checkoutButton.textContent = "CHECKOUT";

    buttonsContainer.append(viewCartButton, checkoutButton);

    cartFooter.append(priceRow, divider, taxShipping, buttonsContainer);

    miniCartMenu.append(cartFooter);
    /*
    Total ---------- $10.00 USD
    ---------------------------
    Taxes and shipping collected at checkout
    VIEW CART | CHECKOUT
    */
  } else {
    const emptyText = document.createElement("p");
    emptyText.textContent = "Your cart is currently empty";

    const shoppingButton = document.createElement("a");
    shoppingButton.textContent = "Browse Products";
    shoppingButton.classList.add("section-redirection-button");

    cartBody.classList.add("empty");
    cartBody.append(emptyText, shoppingButton);
  }

  closeMiniCartMenu.onclick = function () {
    swapClass(miniCartMenu, "slide-out-right", "slide-in-right");
  };

  return miniCartMenu;
}

export function calculateCartTotal(data) {
  const totalPriceTag = document.querySelector(".mini-cart-price");
  console.log(totalPriceTag);

  if (!totalPriceTag) return;

  let total = 0;

  data.forEach((item) => {
    total += parseFloat(item.final_price);
  });

  totalPriceTag.textContent = `$${total.toFixed(2)} USD`;
}
