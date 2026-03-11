import { buildCartCard } from "../components/cartCard.js";
import { getCartItems } from "../services/cartServices.js";
import { calculateCartTotal } from "../components/miniCartBar.js";

const cartOuterContainer = document.querySelector(".cart-outer-container");

if (cartOuterContainer) {
  const response = await getCartItems();
  const { data, status, success } = response;

  renderCartContainer(data);
}

export async function renderCartContainer(data) {
  if (data === null || data === undefined) {
    const response = await getCartItems();
    data = response.data;
  }

  cartOuterContainer.innerHTML = "";
  // TODO : two possible scenarios
  // ! There is data -> we create what we need
  // ! Data is empty
  if (data.length > 0) {
    const cartContainer = createCartContainer(data);
    cartOuterContainer.append(cartContainer);

    const totalContainer = await createTotalContainer(data);
    cartOuterContainer.append(totalContainer);
  } else {
    const emptyCartContainer = createEmptyCartContainer();
    cartOuterContainer.append(emptyCartContainer);
  }
}

function createEmptyCartContainer() {
  const emptyContainer = document.createElement("div");
  emptyContainer.classList.add("empty-cart-container");

  const emptyText = document.createElement("p");
  emptyText.textContent = "Your cart is currently empty.";

  const redirectionButton = document.createElement("a");
  redirectionButton.href = "../pages/products.php";
  redirectionButton.classList.add("empty-container-redirection-button");
  redirectionButton.textContent = "Browse Products";

  emptyContainer.append(emptyText, redirectionButton);

  return emptyContainer;
}

function createCartContainer(data) {
  const cartContainer = document.createElement("div");
  cartContainer.classList.add("cart-container");

  data.forEach(async (item) => {
    const cartCard = await buildCartCard(item);
    cartContainer.append(cartCard);
  });

  return cartContainer;
}

async function createTotalContainer(data) {
  const totalContainer = document.createElement("div");
  totalContainer.classList.add("total-container");

  const totalContainerHeader = document.createElement("div");
  totalContainerHeader.classList.add("total-container-header");

  const totalText = document.createElement("p");
  totalText.textContent = "Total";

  // Get price
  const total = await calculateCartTotal(data);
  const totalPrice = document.createElement("p");
  totalPrice.classList.add("cart-price");
  totalPrice.innerHTML = `<strong> $${total.toFixed(2)} USD </strong>`;

  totalContainerHeader.append(totalText, totalPrice);

  const divider = document.createElement("div");
  divider.classList.add("divider");

  const tax = document.createElement("p");
  tax.classList.add("tax-text");
  tax.textContent = "Taxes and shipping calculated at checkout";

  const checkoutButton = document.createElement("a");
  checkoutButton.href = "../pages/checkout.php";
  checkoutButton.textContent = "Check Out";
  checkoutButton.classList.add("checkout-button");

  totalContainer.append(totalContainerHeader, divider, tax, checkoutButton);

  return totalContainer;
}
