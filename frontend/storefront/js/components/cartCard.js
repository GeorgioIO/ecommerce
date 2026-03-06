import { addToCart_DB } from "../services/cartServices.js";
import { calculateCartTotal } from "./miniCartBart.js";
import { handleCartButton } from "./productCard.js";
import { renderCartContainer } from "../pages/cartPage.js";
export async function buildCartCard(product) {
  const cartCard = document.createElement("div");
  cartCard.classList.add("cart-card");
  cartCard.dataset.productid = product.book_id;

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
  informationContainer.classList.add("cart-card-information");

  const productPriceText = document.createElement("p");
  productPriceText.classList.add("cart-card-price");

  if (product.is_onSale === 1) {
    const preSalePrice = document.createElement("span");
    preSalePrice.dataset.base = product.price;
    preSalePrice.classList.add("pre-sale-price");
    preSalePrice.textContent = `$${product.price}`;

    const postSalePrice = document.createElement("span");
    postSalePrice.classList.add("post-sale-price");
    postSalePrice.dataset.base = product.final_price;
    postSalePrice.textContent = `$${product.final_price}`;

    productPriceText.append(preSalePrice, postSalePrice);
  } else {
    const basePrice = document.createElement("span");
    basePrice.classList.add("base-price");
    basePrice.dataset.base = product.final_price;
    basePrice.textContent = `$${product.final_price}`;

    productPriceText.append(basePrice);
  }

  const textContainer = document.createElement("div");
  textContainer.classList.add("cart-card-text");

  const productTitleText = document.createElement("a");
  productTitleText.href = `../pages/product.php?slug=${product.slug}`;
  productTitleText.classList.add("cart-card-title");
  productTitleText.textContent = product.title;

  textContainer.append(productPriceText, productTitleText);

  // Action container (quantity , remove)
  const actionContainer = document.createElement("div");
  actionContainer.classList.add("cart-card-action");

  // Cart button
  // TODO : if type is search or wishlist we check if its in stock or not + we add wishlist button
  const quantityInput = document.createElement("input");
  quantityInput.type = "number";
  quantityInput.value = product.quantity;
  quantityInput.classList.add("cart-card-item-quantity");
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

    // Update cart card price
    updateCardPrice(cartCard, quantityInput.value);

    // Update total price
    const total = await calculateCartTotal();
    const priceElement = document.querySelector(".cart-price") ?? null;
    if (priceElement)
      priceElement.innerHTML = `<strong>$${total.toFixed(2)} USD </strong>`;
  };

  const removeButton = document.createElement("button");
  removeButton.classList.add("remove-cart-card-button");
  removeButton.textContent = "Remove";
  removeButton.dataset.state = "active";

  removeButton.onclick = async () => {
    await handleCartButton(cartCard, removeButton);
    await renderCartContainer();
  };

  actionContainer.append(quantityInput, removeButton);

  informationContainer.append(textContainer, actionContainer);

  cartCard.append(figure, informationContainer);

  return cartCard;
}

function updateCardPrice(card, quantity) {
  // Get card price
  const cardBasePrice = card.querySelector(".base-price") ?? null;
  const cardPreSalePrice = card.querySelector(".pre-sale-price") ?? null;
  const cardPostSalePrice = card.querySelector(".post-sale-price") ?? null;

  // Extract price
  if (cardBasePrice) {
    let basePrice = parseFloat(cardBasePrice.dataset.base);

    let newBasePrice = (basePrice * parseInt(quantity)).toFixed(2);

    cardBasePrice.textContent = `$${newBasePrice}`;
  }

  if (cardPreSalePrice) {
    let preSalePrice = parseFloat(cardPreSalePrice.dataset.base);
    let postSalePrice = parseFloat(cardPostSalePrice.dataset.base);

    let newPreSalePrice = (preSalePrice * parseInt(quantity)).toFixed(2);
    let newPostSalePrice = (postSalePrice * parseInt(quantity)).toFixed(2);

    cardPreSalePrice.textContent = `$${newPreSalePrice}`;
    cardPostSalePrice.textContent = `$${newPostSalePrice}`;
  }
}
