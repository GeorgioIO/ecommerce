import { createCartButton } from "../core/UIhelpers.js";
import { loadWishlist } from "../pages/wishlistUI.js";
import { addToCart_DB, removeFromCart_DB } from "../services/cartServices.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "./messageBox.js";
import { calculateCartTotal, updateCart } from "./miniCartBart.js";
import { updateMiniWishlistBody } from "./miniWishlistBar.js";
import { handleCartButton, handleWishlistButton } from "./productCard.js";

export async function buildSidebarCard(product, type) {
  // cart , search , wishlist
  const svg_fill = type === "wishlist" || type === "cart" ? "black" : "none";
  const buttons_state =
    type === "wishlist" || type === "cart" ? "active" : "inactive";

  // Create sidebar card
  const sidebarProductCard = document.createElement("div");
  sidebarProductCard.classList.add("sidebar-product-card");
  sidebarProductCard.dataset.productid = product.book_id;

  // Figure
  const figure = document.createElement("figure");
  const imageAnchorTag = document.createElement("a");
  console.log(imageAnchorTag);
  imageAnchorTag.href = `../pages/product.php?slug=${product.slug}`;
  const image = document.createElement("img");
  image.src = "../../../assets/images/" + product.cover_image;
  image.alt = `${product.title} cover image`;

  imageAnchorTag.append(image);
  figure.append(imageAnchorTag);

  // information section
  const informationContainer = document.createElement("div");
  informationContainer.classList.add("sidebar-product-card-information");

  const textContainer = document.createElement("div");
  textContainer.classList.add("sidebar-product-card-text");

  const productPriceText = document.createElement("p");
  productPriceText.classList.add("sidebar-product-card-price");

  if (product.is_onSale === 1) {
    const preSalePrice = document.createElement("span");
    preSalePrice.classList.add("pre-sale-price");
    preSalePrice.textContent = `$${product.price}`;

    const postSalePrice = document.createElement("span");
    postSalePrice.classList.add("post-sale-price");
    postSalePrice.textContent = `$${product.final_price}`;

    productPriceText.append(preSalePrice, postSalePrice);
  } else {
    const basePrice = document.createElement("span");
    basePrice.classList.add("base-price");
    basePrice.textContent = `$${product.final_price}`;

    productPriceText.append(basePrice);
  }

  const productTitleText = document.createElement("a");
  productTitleText.classList.add("sidebar-product-card-title");
  productTitleText.textContent = product.title;

  textContainer.append(productPriceText, productTitleText);

  // Action container
  const actionContainer = document.createElement("div");
  actionContainer.classList.add("sidebar-product-card-action");

  // Cart button
  // TODO : if type is search or wishlist we check if its in stock or not + we add wishlist button
  if (type === "search" || type === "wishlist") {
    if (product.is_inStock === 0) {
      const outOfStockButton = document.createElement("button");
      outOfStockButton.classList.add("out-of-stock-button");
      outOfStockButton.innerHTML = "Out of stock";

      actionContainer.append(outOfStockButton);
    } else {
      const cartButton = createCartButton("product-card-add-cart-button");
      actionContainer.append(cartButton);
    }

    // ! Wishlist button for search + wishlist
    const wishlistButton = document.createElement("button");
    wishlistButton.classList.add("product-card-add-wishlist-button");
    wishlistButton.dataset.enabled = "true";
    wishlistButton.dataset.state = buttons_state;
    wishlistButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${svg_fill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
    </svg>
  `;

    wishlistButton.onclick = async () => {
      // From wishlist
      if (type === "wishlist") {
        await handleWishlistButton(sidebarProductCard, wishlistButton);
        await updateMiniWishlistBody();
        if (window.location.pathname.includes("wishlist.php")) {
          await loadWishlist();
        }
      }
    };
    actionContainer.prepend(wishlistButton);
  } else {
    const cartButton = createCartButton("product-card-add-cart-button", "cart");
    actionContainer.append(cartButton);

    cartButton.onclick = async () => {
      await handleCartButton(sidebarProductCard, cartButton);
      // await removeFromCart_DB(product.book_id);
      await updateCart();
      await calculateCartTotal();
    };
  }

  if (type === "cart") {
    const quantityInput = document.createElement("input");
    quantityInput.type = "number";
    quantityInput.value = product.quantity;
    quantityInput.classList.add("cart-item-quantity");
    quantityInput.step = 1;
    quantityInput.min = 1;

    quantityInput.onchange = async () => {
      const response = await addToCart_DB(product.book_id, quantityInput.value);

      if (!response.success) {
        activateMessageBox();
        const messageBox = createMesssageBox(response.message);
        appendMessageBox(messageBox);
        return;
      }
      await updateCart();
      await calculateCartTotal(response.data);
    };

    actionContainer.append(quantityInput);
  }

  informationContainer.append(textContainer, actionContainer);
  sidebarProductCard.append(figure, informationContainer);

  return sidebarProductCard;
}
