import { handleWishlistButton } from "./productCard.js";

export function buildSidebarCard(product, type) {
  const svg_fill = type === "wishlist" ? "black" : "none";
  const buttons_state = type === "wishlist" ? "active" : "inactive";

  // Create sidebar card
  const sidebarProductCard = document.createElement("div");
  sidebarProductCard.classList.add("sidebar-product-card");
  sidebarProductCard.dataset.productid = product.book_id;

  // Figure
  const figure = document.createElement("figure");
  const image = document.createElement("img");
  image.src = "../../../assets/images/" + product.cover_image;
  image.alt = `${product.title} cover image`;

  figure.append(image);

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

  const wishlistButton = document.createElement("button");
  wishlistButton.classList.add("product-card-add-wishlist-button");
  wishlistButton.dataset.enabled = "true";
  wishlistButton.dataset.state = buttons_state;
  wishlistButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${svg_fill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
    </svg>
  `;

  wishlistButton.onclick = () => {
    handleWishlistButton(sidebarProductCard, wishlistButton);
    setTimeout(() => {
      sidebarProductCard.remove();
    }, 50);
  };

  const cartButton = document.createElement("button");
  cartButton.classList.add("product-card-add-cart-button");
  cartButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
      </svg>
  `;

  actionContainer.append(wishlistButton, cartButton);

  informationContainer.append(textContainer, actionContainer);
  sidebarProductCard.append(figure, informationContainer);

  return sidebarProductCard;
}
