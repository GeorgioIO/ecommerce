import { listState } from "../pages/wishlistUI.js";
import {
  removeFromWishlist,
  addToWishlist,
  getWishlistItems,
} from "../services/wishlistServices.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "./messageBox.js";
import { renderProductsCatalog } from "./productsCatalog.js";

document.addEventListener("click", async (e) => {
  const removeFromWishlistButton = e.target.closest(
    ".product-card-remove-wishlist-button",
  );

  if (removeFromWishlistButton) {
  }
});

// ========== EXPORTED FUNCTIONS ==========

export function buildProductCard(product, type = "normal") {
  // This function is responsible of building a single product card
  // Input : Product data (id , title...)
  // Output : Product card element
  // There is multiple types of the same product card
  /*
    Normal : the default product card style for home , products section
    Wishlist : wishlist product card style for wishlist page , come without product card actions , main buttons are add to card and remove from wishlist
  */

  // Product card
  const productCard = document.createElement("div");
  productCard.classList.add("product-card");
  productCard.dataset.productid = product.book_id;

  // Figure
  const figure = document.createElement("figure");
  const imageAnchorTag = document.createElement("a");
  const image = document.createElement("img");
  image.src = "../../../assets/images/" + product.cover_image;
  image.alt = `${product.title} book cover`;

  imageAnchorTag.append(image);

  figure.append(imageAnchorTag);

  if (type === "normal") {
    const productCardActions = document.createElement("div");
    productCardActions.classList.add("product-card-action");

    // add to wishlist button
    const addToWishlistButton = document.createElement("button");
    addToWishlistButton.classList.add("product-card-add-wishlist-button");
    addToWishlistButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
    </svg>
    `;

    setAddToWishlistButtonEvent(addToWishlistButton);

    // add to card button
    const addToCartButton = document.createElement("button");
    addToCartButton.classList.add("product-card-add-cart-button");
    addToCartButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
      </svg>
    `;

    if (product.is_inStock === 0) {
      addToCartButton.classList.add("unclickable");
      addToCartButton.disabled = true;
    }

    productCardActions.append(addToWishlistButton, addToCartButton);

    figure.append(productCardActions);
  }

  // Product card text area
  const productCardText = document.createElement("div");
  productCardText.classList.add("product-card-text");

  const productCardPrice = document.createElement("p");
  productCardPrice.classList.add("product-card-price");

  if (product.is_onSale === 1) {
    // Sale badge
    const productSaleBadge = document.createElement("div");
    productSaleBadge.classList.add("product-card-sale-badge");
    productSaleBadge.textContent = `%${product.discount_percentage}`;

    figure.append(productSaleBadge);

    // Sale price
    const preSalePrice = document.createElement("span");
    preSalePrice.classList.add("pre-sale-price");
    preSalePrice.textContent = `$${product.price}`;

    const postSalePrice = document.createElement("span");
    postSalePrice.classList.add("post-sale-price");
    postSalePrice.textContent = `$${product.final_price}`;

    productCardPrice.append(preSalePrice, postSalePrice);
  } else {
    const basePrice = document.createElement("span");
    basePrice.classList.add("base-price");
    basePrice.textContent = `$${product.final_price}`;

    productCardPrice.append(basePrice);
  }

  // Product title
  const titleAnchorTag = document.createElement("a");
  titleAnchorTag.classList.add("product-card-title");
  titleAnchorTag.textContent = product.title;

  // Product author and format
  const authorAndFormat = document.createElement("p");
  authorAndFormat.classList.add("product-card-author-format");

  const authorAnchorTag = document.createElement("a");
  authorAnchorTag.classList.add("product-card-author");
  authorAnchorTag.innerHTML = `By <span class="product-card-author-name"> ${product.author_name} </span>`;

  const separator = document.createElement("span");
  separator.classList.add("separator");
  separator.textContent = ",";

  const productCardFormat = document.createElement("div");
  productCardFormat.classList.add("product-card-format");
  productCardFormat.textContent = product.format_name;

  authorAndFormat.append(authorAnchorTag, separator, productCardFormat);

  productCardText.append(productCardPrice, titleAnchorTag, authorAndFormat);

  productCard.append(figure, productCardText);

  if (type === "normal") {
    if (product.is_inStock === 0) {
      const soldOutButton = document.createElement("button");
      soldOutButton.disabled = true;
      soldOutButton.classList.add("sold-out-button");

      productCard.append(soldOutButton);
    } else {
      const redirectionButton = document.createElement("a");
      redirectionButton.classList.add("product-card-redirection-button");
      redirectionButton.textContent = "View Product";
    }

    productCard.append(redirectionButton);
  } else if (type === "wishlist") {
    if (product.is_inStock === 0) {
      const soldOutButton = document.createElement("button");
      soldOutButton.disabled = true;
      soldOutButton.textContent = "Sold Out";
      soldOutButton.classList.add("sold-out-button");

      productCard.append(soldOutButton);
    } else {
      const addToCardButton = document.createElement("a");
      addToCardButton.classList.add("product-card-add-to-card-button");
      addToCardButton.textContent = "Add To Cart";

      productCard.append(addToCardButton);
    }

    const removeFromWishlistButton = document.createElement("button");
    removeFromWishlistButton.classList.add(
      "product-card-remove-wishlist-button",
    );
    removeFromWishlistButton.textContent = "Remove";

    setRemoveFromWishlistEvent(removeFromWishlistButton, listState);
    productCard.append(removeFromWishlistButton);
  }

  return productCard;
}

async function setAddToWishlistButtonEvent(button) {
  button.onclick = async () => {};
}

export async function handleWishlistButton(card, button) {
  /*
    Two states : button is active (in wishlist) or inactive (not in wishlist)
  */
  // First check if clicking is enabled (user logged in)
  // Check what state the button is int
  // If active remove item from wishlist
  // If inactive add item to wishlist
  const canAddToWishlist = button.dataset.enabled === "true" ? true : false;
  // Log in required
  activateMessageBox();
  if (!canAddToWishlist) {
    const messageBox = createMesssageBox("Log in required");
    appendMessageBox(messageBox);
    return;
  }

  const state = button.dataset.state;

  if (state === "inactive") {
    const productid = card.dataset.productid;
    const response = await addToWishlist(productid);

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);

    if (response.success) {
      button.dataset.state = "active";
    }
  } else if (state === "active") {
  }
}

async function setRemoveFromWishlistEvent(button, state) {
  button.onclick = async (e) => {
    // Get section
    const closestSection = e.target.closest("section");
    const card = e.target.closest(".product-card");

    // Get product id
    const productid = card.dataset.productid;

    // Response
    const deleteResponse = await removeFromWishlist(productid);

    if (deleteResponse.success) {
      const fetchResponse = await getWishlistItems({
        page: listState.page,
        perPage: listState.perPage,
      });

      const { data, pagination } = fetchResponse;

      if (state.page > pagination.totalPages) {
        state.page = pagination.totalPages || 1;
      }

      activateMessageBox();
      const messageBox = createMesssageBox(deleteResponse.message);
      appendMessageBox(messageBox);

      await renderProductsCatalog(closestSection, state);
    } else {
      activateMessageBox();

      const messageBox = createMesssageBox(deleteResponse.message);

      appendMessageBox(messageBox);
    }
  };
}
