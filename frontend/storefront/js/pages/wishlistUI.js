import { createPaginationContainer } from "../components/pagination.js";
import { buildProductCard } from "../components/productCard.js";
import { getWishlistItems } from "../services/wishlistServices.js";

export const listState = {
  page: 1,
  perPage: 5,
  totalItems: 0,
  totalPages: 1,
};

const wishlistSection = document.querySelector("#wishlist") ?? null;

document.addEventListener("DOMContentLoaded", async () => {
  if (wishlistSection) {
    // Load wishlist_items
    const wishlist_items = await getWishlistItems({
      page: listState.page,
      perPage: listState.perPage,
    });

    const paginationData = wishlist_items.pagination;

    listState.page = paginationData.page;
    listState.totalPages = paginationData.totalPages;

    populateWishlistProductGrid(wishlist_items.data);

    const paginationContainer = createPaginationContainer(
      paginationData,
      listState,
    );

    if (paginationContainer) {
      wishlistSection.append(paginationContainer);
    }
  }
});

export async function loadWishlist() {
  console.log("Before : ", listState);
  const wishlistItems = await getWishlistItems({
    page: listState.page,
    perPage: listState.perPage,
  });

  const paginationData = wishlistItems.pagination;

  console.log("After : ", listState);

  const wishlistContainer = document.querySelector(".products-grid");
  wishlistContainer.innerHTML = "";

  populateWishlistProductGrid(wishlistItems.data);

  const paginationContainer = createPaginationContainer(
    paginationData,
    listState,
  );

  if (paginationContainer) {
    wishlistSection.append(paginationContainer);
  }
}

function populateWishlistProductGrid(products) {
  console.log(products);
  const productsGrid = document.querySelector(".products-grid");
  productsGrid.innerHTML = "";

  products.forEach((product) => {
    const producCard = buildProductCard(product, "wishlist");
    productsGrid.append(producCard);
  });
}
