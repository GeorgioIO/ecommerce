import { loadWishlist, wishlistListState } from "../pages/wishlistUI.js";
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
import { getSession } from "../services/sessionServices.js";
import { addToCart_DB, removeFromCart_DB } from "../services/cartServices.js";
import { calculateCartTotal, updateCart } from "./miniCartBar.js";

// ========== EXPORTED FUNCTIONS ==========

const sessionData = await getSession();

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
  const bookid = product.book_id ? product.book_id : product.id;
  const productCard = document.createElement("div");
  productCard.classList.add("product-card");
  productCard.dataset.productid = bookid;

  // Figure
  const figure = document.createElement("figure");
  const imageAnchorTag = document.createElement("a");
  imageAnchorTag.href = `../pages/product.php?slug=${product.slug}`;
  const image = document.createElement("img");
  image.src = "../../../assets/images/" + product.cover_image;
  image.alt = `${product.title} book cover`;

  imageAnchorTag.append(image);

  figure.append(imageAnchorTag);

  if (type === "normal") {
    const productCardActions = document.createElement("div");
    productCardActions.classList.add("product-card-actions");

    // add to wishlist button
    const addToWishlistButton = document.createElement("button");
    addToWishlistButton.classList.add("product-card-add-wishlist-button");
    let wishlistIconFill = "none";
    let cartIconFill = "none";
    addToWishlistButton.onclick = () => {
      handleWishlistButton(productCard, addToWishlistButton);
    };

    // add to card button
    const addToCartButton = document.createElement("button");
    addToCartButton.classList.add("product-card-add-cart-button");

    addToCartButton.onclick = async () => {
      await handleCartButton(productCard, addToCartButton);
      await updateCart();
      const total = await calculateCartTotal();
      const priceElement = document.querySelector(".mini-cart-price") ?? null;
      if (priceElement) priceElement.textContent = `$${total.toFixed(2)} USD`;
    };

    if (!sessionData.session.user_id && !sessionData.cookie.username) {
      addToWishlistButton.dataset.enabled = "false";
      addToWishlistButton.dataset.state = "inactive";

      addToCartButton.dataset.enabled = "false";
      addToCartButton.dataset.state = "inactive";
    } else {
      addToWishlistButton.dataset.enabled = "true";
      addToCartButton.dataset.enabled = "true";
      if (product.is_inWishlist) {
        addToWishlistButton.dataset.state = "active";
        wishlistIconFill = "black";
      } else {
        addToWishlistButton.dataset.state = "inactive";
      }

      if (product.is_inCart) {
        addToCartButton.dataset.state = "active";
        cartIconFill = "black";
      } else {
        addToCartButton.dataset.state = "inactive";
      }
    }

    addToWishlistButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${wishlistIconFill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.694C10 3 3 3.5 3 9.5s9 11 9 11 9-5 9-11-7-6.5-9-1.806Z"/>
    </svg>
    `;

    addToCartButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${cartIconFill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
      </svg>
    `;

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
  titleAnchorTag.href = `../pages/product.php?slug=${product.slug}`;
  titleAnchorTag.classList.add("product-card-title");
  titleAnchorTag.textContent = product.title;

  // Product author and format
  const authorAndFormat = document.createElement("p");
  authorAndFormat.classList.add("product-card-author-format");

  const authorAnchorTag = document.createElement("a");
  authorAnchorTag.href = `../pages/products.php?author=${product.author_id}`;
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
      soldOutButton.textContent = "Sold Out";
      productCard.append(soldOutButton);
    } else {
      const redirectionButton = document.createElement("a");
      redirectionButton.href = `../pages/product.php?slug=${product.slug}`;
      redirectionButton.classList.add("product-card-redirection-button");
      redirectionButton.textContent = "View Product";
      productCard.append(redirectionButton);
    }
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

    setRemoveFromWishlistEvent(removeFromWishlistButton, wishlistListState);
    productCard.append(removeFromWishlistButton);
  }

  return productCard;
}

export async function handleCartButton(product, button) {
  /*
  Condition for cart to work :
    - User logged in (1)
    - Then we add 

  Condition for cart not to work :
    - User is not logged in
    - if logged in if the book is out of stock

  state : 
  */
  const currentSessionData = await getSession();
  const isLoggedIn =
    currentSessionData.session.user_id || currentSessionData.cookie.username;

  activateMessageBox();

  if (!isLoggedIn) {
    const messageBox = createMesssageBox("Log in required");
    appendMessageBox(messageBox);
    return false;
  }

  const state = button.dataset.state ?? "inactive";
  const productid = product.dataset.productid;
  const svg = button.querySelector("svg") ?? null;

  const quantityInput =
    document.querySelector("#single-product-quantity") ?? null;
  let quantity = 1;

  if (quantityInput) {
    quantity = quantityInput.value;
  }

  // Book is not in cart
  if (state === "inactive" || button.id === "single-product-adc-button") {
    const response = await addToCart_DB(productid, quantity);
    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);

    if (response.success) {
      button.dataset.state = "active";
      if (svg) svg.setAttribute("fill", "black");
      return {
        success: true,
        message: response.message,
      };
    } else {
      return {
        success: false,
        message: response.message,
      };
    }
  }
  // Book is already in cart
  else if (state === "active") {
    const response = await removeFromCart_DB(productid);
    const addToCardButton =
      document.querySelector("#single-product-adc-button") ?? null;
    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);

    if (response.success) {
      button.dataset.state = "inactive";

      if (addToCardButton && addToCardButton.dataset.state === "active") {
        addToCardButton.dataset.state = "inactive";
      }

      if (svg) svg.setAttribute("fill", "none");

      return {
        success: true,
        message: response.message,
      };
    } else {
      return {
        success: false,
        message: response.message,
      };
    }
  }
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
  const productid = card.dataset.productid;
  const svg = button.querySelector("svg");

  if (state === "inactive") {
    const response = await addToWishlist(productid);

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);

    if (response.success) {
      button.dataset.state = "active";
      svg.setAttribute("fill", "black");
    }
  } else if (state === "active") {
    const response = await removeFromWishlist(productid);

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);

    if (response.success) {
      button.dataset.state = "inactive";
      svg.setAttribute("fill", "none");
    }
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
        page: wishlistListState.page,
        perPage: wishlistListState.perPage,
      });

      const { data, pagination } = fetchResponse;

      if (state.page > pagination.totalPages) {
        state.page = pagination.totalPages || 1;
      }

      await loadWishlist();

      activateMessageBox();
      const messageBox = createMesssageBox(deleteResponse.message);
      appendMessageBox(messageBox);
    } else {
      activateMessageBox();

      const messageBox = createMesssageBox(deleteResponse.message);

      appendMessageBox(messageBox);
    }
  };
}
