import { calculateCartTotal, updateCart } from "../components/miniCartBar.js";
import { handleCartButton } from "../components/productCard.js";

document.addEventListener("click", async (e) => {
  const addToCartButton = e.target.closest("#single-product-adc-button");

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
