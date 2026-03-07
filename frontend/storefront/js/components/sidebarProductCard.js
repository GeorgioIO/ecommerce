import {
  createCartButton,
  createOutOfStockButton,
  createWishlistButton,
} from "../core/UIhelpers.js";
import { addToCart_DB } from "../services/cartServices.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "./messageBox.js";
import { calculateCartTotal, updateCart } from "./miniCartBar.js";

export async function buildSidebarCard(product, type) {
  // cart , search , wishlist
  let wishlistSvgFill = product.is_inWishlist === 1 ? "black" : "none";
  let cartSvgFill = product.is_inCart === 1 ? "black" : "none";
  let wishlistButtonState = product.is_inWishlist === 1 ? "active" : "inactive";
  let cartButtonState = product.is_inCart === 1 ? "active" : "inactive";

  if (type === "wishlist") {
    wishlistButtonState = "active";
    wishlistSvgFill = "black";
  }

  if (type === "cart") {
    cartButtonState = "active";
    cartSvgFill = "black";
  }

  // Create sidebar card
  const sidebarProductCard = document.createElement("div");
  sidebarProductCard.classList.add("sidebar-product-card");
  sidebarProductCard.dataset.productid = product.book_id;

  // Figure
  const figure = document.createElement("figure");
  const imageAnchorTag = document.createElement("a");

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
  if (type === "search") {
    // ! Cart Button
    if (product.is_inStock === 1) {
      const cartButton = createCartButton(
        sidebarProductCard,
        "product-card-add-cart-button",
        null,
        cartButtonState,
        cartSvgFill,
      );
      actionContainer.append(cartButton);
    } else {
      const outOfStockButton = createOutOfStockButton("out-of-stock-button");
      actionContainer.append(outOfStockButton);
    }

    // ! Wishlist Button
    const wishlistButton = createWishlistButton(
      sidebarProductCard,
      "product-card-add-wishlist-button",
      type,
      wishlistButtonState,
      wishlistSvgFill,
    );
    actionContainer.prepend(wishlistButton);
  }

  if (type === "wishlist") {
    console.log(wishlistButtonState);
    const wishlistButton = createWishlistButton(
      sidebarProductCard,
      "product-card-add-wishlist-button",
      type,
      wishlistButtonState,
      wishlistSvgFill,
    );
    actionContainer.prepend(wishlistButton);

    if (product.is_inStock === 1) {
      const cartButton = createCartButton(
        sidebarProductCard,
        "product-card-add-cart-button",
        null,
        cartButtonState,
        cartSvgFill,
      );
      actionContainer.append(cartButton);
    } else {
      const outOfStockButton = createOutOfStockButton("out-of-stock-button");
      actionContainer.append(outOfStockButton);
    }
  }

  if (type === "cart") {
    const cartButton = createCartButton(
      sidebarProductCard,
      "product-card-add-cart-button",
      "cart",
      cartButtonState,
      cartSvgFill,
    );
    actionContainer.append(cartButton);
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
      const total = await calculateCartTotal();
      const priceElement = document.querySelector(".mini-cart-price") ?? null;
      if (priceElement) priceElement.textContent = `$${total.toFixed(2)} USD`;
    };

    actionContainer.append(quantityInput);
  }

  informationContainer.append(textContainer, actionContainer);
  sidebarProductCard.append(figure, informationContainer);

  return sidebarProductCard;
}
