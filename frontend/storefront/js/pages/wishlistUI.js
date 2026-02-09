import { renderProductsCatalog } from "../components/productsCatalog.js";

export const listState = {
  page: 1,
  perPage: 5,
};

const wishlistSection = document.querySelector("#wishlist") ?? null;

document.addEventListener("DOMContentLoaded", async () => {
  if (wishlistSection) {
    await renderProductsCatalog(wishlistSection, listState);
  }
});
