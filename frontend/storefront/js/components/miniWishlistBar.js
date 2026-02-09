import { swapClass } from "../../../admin/js/UIhelpers.js";
import { getWishlistItems } from "../services/wishlistServices.js";
import { buildSidebarCard } from "./sidebarProductCard.js";

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

export async function createMiniWishlistContainer() {
  const wishlistItemsContainer = document.createElement("div");
  wishlistItemsContainer.classList.add("wishlist-items-container");

  const wishlist_items = await getWishlistItems();
  const data = wishlist_items.data;

  data.forEach((product) => {
    wishlistItemsContainer.append(buildSidebarCard(product, "wishlist"));
  });

  return wishlistItemsContainer;
}

export function createViewWishlistButton() {
  const viewWishlistButton = document.createElement("a");
  viewWishlistButton.classList.add("view-more-button");
  viewWishlistButton.href = "../pages/wishlist.php";
  viewWishlistButton.textContent = "View wishlist";
  return viewWishlistButton;
}
