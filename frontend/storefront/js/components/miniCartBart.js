import { swapClass } from "../../../admin/js/UIhelpers.js";
import { getCartItems } from "../services/cartServices.js";
import { buildSidebarCard } from "./sidebarProductCard.js";

export async function renderMiniCartBar(body = null) {
  if (!body) body = document.body;
  // Get cart data
  const { data, success, status, message } = await getCartItems();
  let dataAvailable = false;
  if (data.length > 0) dataAvailable = true;

  // Create cart bar
  const miniCart = BuildMiniCartBar(dataAvailable);

  // Get cart body
  const cartBody = miniCart.querySelector(".mini-cart-body");
  const cartFooter = miniCart.querySelector(".mini-cart-footer");

  body.append(miniCart);

  if (success && data.length > 0) {
    const cartItemsContainer = createCartItemsContainer(data);
    cartBody.append(cartItemsContainer);
    cartFooter.style.display = "flex";
    const total = await calculateCartTotal();
    const priceElement = document.querySelector(".mini-cart-price") ?? null;
    if (priceElement) priceElement.textContent = `$${total.toFixed(2)} USD`;
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

export async function updateCart() {
  // TODO : After each delete it checks if cart becomes empty
  const { data } = await getCartItems();
  const cartBody = document.querySelector(".mini-cart-body") ?? null;
  const cartFooter = document.querySelector(".mini-cart-footer") ?? null;
  const cartItemsContainer =
    document.querySelector(".mini-cart-items-container") ?? null;
  let emptyText = document.querySelector(".empty-cart-text") ?? null;
  let shoppingButton =
    document.querySelector(".mini-cart-body .section-redirection-button") ??
    null;

  if (data !== null && data.length === 0) {
    if (cartItemsContainer) cartItemsContainer.innerHTML = "";
    if (!emptyText && !shoppingButton) {
      emptyText = document.createElement("p");
      emptyText.classList.add("empty-cart-text");
      emptyText.textContent = "Your cart is currently empty";

      shoppingButton = document.createElement("a");
      shoppingButton.href = "../pages/products.php";
      shoppingButton.textContent = "Browse Products";
      shoppingButton.classList.add("section-redirection-button");
      cartBody?.append(emptyText, shoppingButton);
    } else {
      emptyText.style.display = "flex";
      shoppingButton.style.display = "flex";
    }

    cartBody?.classList.add("empty");
    cartFooter.style.display = "none";
  } else {
    if (!cartItemsContainer) return;
    if (emptyText && shoppingButton) {
      emptyText.style.display = "none";
      shoppingButton.style.display = "none";
    }

    cartBody.classList.remove("empty");
    cartFooter.style.display = "flex";
    cartItemsContainer.innerHTML = "";

    data.forEach(async (item) => {
      const sidebarCard = await buildSidebarCard(item, "cart");
      cartItemsContainer.append(sidebarCard);
    });
  }
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
    cartFooter.style.display = "none";

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
    viewCartButton.href = "../pages/cart.php";
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
    shoppingButton.href = "../pages/products.php";
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

export async function calculateCartTotal(data = null) {
  let backupResponse;
  if (!data) {
    backupResponse = await getCartItems();
    data = backupResponse.data;
  }

  if (data.length === 0) return;
  let total = 0;

  data.forEach((item) => {
    total += parseFloat(item.final_price);
  });

  return total;
}
