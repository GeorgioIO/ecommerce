import { updateCardPrice } from "../components/cartCard.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "../components/messageBox.js";
import { calculateCartTotal, updateCart } from "../components/miniCartBar.js";
import { handleCartButton } from "../components/productCard.js";

document.addEventListener("click", async (e) => {
  const addToCartButton = e.target.closest("#single-product-adc-button");
  const buyNowButton = e.target.closest("#buy-now-button");

  if (buyNowButton) {
    const product = e.target.closest("#product");
    const response = await handleCartButton(product, buyNowButton);

    if (response.success) {
      window.location.href = "../pages/checkout.php";
    } else {
      activateMessageBox();
      const messageBox = createMesssageBox(response.message);
      appendMessageBox(messageBox);
      return;
    }
  }

  if (addToCartButton) {
    const product = e.target.closest("#product");
    const response = await handleCartButton(product, addToCartButton);
    if (response.success) {
      await updateCart();
      const total = await calculateCartTotal();
      const priceElement = document.querySelector(".mini-cart-price") ?? null;
      if (priceElement) priceElement.textContent = `$${total.toFixed(2)} USD`;
    }
  }
});

document.addEventListener("change", (e) => {
  const quantity = document.querySelector("#single-product-quantity");
  if (quantity) {
    const product = document.querySelector("#product");
    updateCardPrice(product, quantity.value);
  }
});
