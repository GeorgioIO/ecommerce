import { swapClass } from "../../../admin/js/UIhelpers.js";
import { getWishlistItems } from "../services/wishlistServices.js";
import { buildSidebarCard } from "./sidebarProductCard.js";

export async function renderMiniWishlist(body) {
  // Create wishlist sidebar
  const miniWishlist = buildMiniWishlistBar();

  // Get the wishlist body
  const wishlistBody = miniWishlist.querySelector(".mini-wishlist-body");

  // Get wishlist data
  const { data, success, status, message } = await getWishlistItems();

  // populate it with data
  const itemsContainer = await createMiniWishlistContainer(
    data,
    success,
    status,
    message,
  );

  // Create view wishlist button
  if (success && data.length > 0) {
    const viewWishlistButton = createViewWishlistButton();
    wishlistBody.append(itemsContainer, viewWishlistButton);
  } else {
    wishlistBody.append(itemsContainer);
  }

  miniWishlist.append(wishlistBody);

  // append to body
  body.append(miniWishlist);

  // show it
  swapClass(miniWishlist, "slide-in-right", "slide-out-right");
}

export function buildMiniWishlistBar() {
  let miniWishlist = document.querySelector("#mini-wishlist-bar") ?? null;

  if (miniWishlist) {
    miniWishlist.remove();
  }

  miniWishlist = document.createElement("div");
  miniWishlist.id = "mini-wishlist-bar";

  // Header
  const miniWishlistHeader = document.createElement("header");
  miniWishlistHeader.classList.add("mini-wishlist-bar-header");

  const miniWishlistTitle = document.createElement("h2");
  miniWishlistTitle.classList.add("mini-wishlist-bar-title");
  miniWishlistTitle.textContent = "WISHLIST";

  const closeWishlistBar = document.createElement("button");
  closeWishlistBar.id = "close-mini-wishlist-button";
  closeWishlistBar.type = "button";
  closeWishlistBar.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
    </svg>
  `;

  miniWishlistHeader.append(miniWishlistTitle, closeWishlistBar);

  const wishlistlistBody = document.createElement("div");
  wishlistlistBody.classList.add("mini-wishlist-body");

  closeWishlistBar.onclick = function () {
    swapClass(miniWishlist, "slide-out-right", "slide-in-right");
  };

  miniWishlist.append(miniWishlistHeader, wishlistlistBody);

  return miniWishlist;
}

export async function createMiniWishlistContainer(
  data,
  success,
  status,
  message,
) {
  const wishlistItemsContainer = document.createElement("div");
  wishlistItemsContainer.classList.add("wishlist-items-container");

  if (success && data.length > 0) {
    data.forEach(async (product) => {
      wishlistItemsContainer.append(
        await buildSidebarCard(product, "wishlist"),
      );
    });
  } else if (success && data.length === 0) {
    // WISHLIST EMPTY
    const emptyText = document.createElement("a");
    emptyText.classList.add("empty-text");
    emptyText.href = "../pages/products.php";
    emptyText.textContent = "No product in your wishlist";
    wishlistItemsContainer.append(emptyText);
  } else if (!success && status === 401) {
    // USER NOT LOGGED IN
    const loginReminder = document.createElement("a");
    loginReminder.classList.add("login-reminder");
    loginReminder.href = "../pages/my-account.php";
    loginReminder.textContent = message;
    wishlistItemsContainer.append(loginReminder);
  }

  return wishlistItemsContainer;
}

export async function updateMiniWishlistBody() {
  const miniWishlistBody = document.querySelector(".mini-wishlist-body");

  if (!miniWishlistBody) return;

  const itemsContainer = miniWishlistBody.querySelector(
    ".wishlist-items-container",
  );

  // Get wishlist data
  const { data, success } = await getWishlistItems();
  itemsContainer.innerHTML = "";
  if (success && data.length > 0) {
    data.forEach(async (product) => {
      itemsContainer.append(await buildSidebarCard(product, "wishlist"));
    });

    if (data.length === 1) {
      const viewMoreButton =
        miniWishlistBody.querySelector(".view-more-button");
      if (viewMoreButton) return;
      miniWishlistBody.append(createViewWishlistButton());
    }
  } else if (success && data.length === 0) {
    // WISHLIST EMPTY
    const emptyText = document.createElement("a");
    emptyText.classList.add("empty-text");
    emptyText.href = "../pages/products.php";
    emptyText.textContent = "No product in your wishlist";
    itemsContainer.append(emptyText);

    miniWishlistBody.querySelector(".view-more-button").remove();
  }
}

export function createViewWishlistButton() {
  const viewWishlistButton = document.createElement("a");
  viewWishlistButton.classList.add("view-more-button");
  viewWishlistButton.href = "../pages/wishlist.php";
  viewWishlistButton.textContent = "View wishlist";
  return viewWishlistButton;
}
