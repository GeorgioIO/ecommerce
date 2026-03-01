import { handlePaginationButtonsColor } from "../../../admin/js/UIhelpers.js";
import { renderProductsCatalog } from "../components/productsCatalog.js";
import { getWishlistItems } from "../services/wishlistServices.js";
export const wishlistListState = {
  page: 1,
  perPage: 5,
};

const wishlistSection = document.querySelector("#wishlist") ?? null;
if (wishlistSection) {
  await loadWishlist();
}

export async function loadWishlist() {
  const response = await getWishlistItems({
    page: wishlistListState.page,
    perPage: wishlistListState.perPage,
  });

  const { data, pagination } = response;

  wishlistListState.totalPages = pagination.totalPages;
  wishlistListState.totalItems = pagination.total;

  if (wishlistListState.page > pagination.totalPages) {
    wishlistListState.page = pagination.totalPages;
  }

  await renderProductsCatalog(
    wishlistSection,
    data,
    "wishlist",
    pagination,
    wishlistListState,
  );

  handlePaginationButtonsColor(wishlistListState.page);
}
