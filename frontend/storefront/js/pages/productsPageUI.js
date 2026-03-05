import { renderProductsCatalog } from "../components/productsCatalog.js";
import { getBooks_DB } from "../services/booksServices.js";
import { handlePaginationButtonsColor } from "../../../admin/js/UIhelpers.js";
import { currentFilters } from "../core/currentFilters.js";
export const productsListState = {
  page: 1,
  perPage: 20,
};

const productsSection = document.querySelector("#products") ?? null;

document.addEventListener("DOMContentLoaded", async () => {
  if (productsSection) {
    const params = new URLSearchParams(window.location.search);
    let genreFromURL = params.get("genre") ?? null;
    let authorFormURL = params.get("author") ?? null;

    if (genreFromURL) currentFilters.genre = genreFromURL;
    if (authorFormURL) currentFilters.author = authorFormURL;

    await loadProducts();
    handlePaginationButtonsColor(productsListState.page);
  }
});

document.addEventListener("change", async () => {
  // Collect all filters
  const form = document.querySelector("#filtering-form");

  if (!form) return;

  Object.assign(currentFilters, {
    sortOption: form.querySelector("#sortOption").value ?? null,
    minPrice: form.querySelector("#minPrice").value ?? null,
    maxPrice: form.querySelector("#maxPrice").value ?? null,
    author: form.querySelector("#author").value ?? null,
    genre: form.querySelector("#genre").value ?? null,
    format: form.querySelector("#format").value ?? null,
    language: form.querySelector("#language").value ?? null,
  });

  const inStock = form.querySelector("#available-checkbox");
  const outOfStock = form.querySelector("#outofstock-checkbox");

  if (inStock.checked) {
    currentFilters.stock = 1;
  } else if (outOfStock.checked) {
    currentFilters.stock = 0;
  }

  // Reseting page to one
  productsListState.page = 1;

  await loadProducts();
});

export function updateProductCount(productsCount) {
  const productsCountElement = document.querySelector(".products-count");
  productsCountElement.textContent = productsCount;
}

export async function loadProducts() {
  const response = await getBooks_DB(currentFilters, {
    page: productsListState.page,
    perPage: productsListState.perPage,
  });

  const { data, pagination } = response;

  console.log(data);

  productsListState.totalPages = pagination.totalPages;
  productsListState.totalItems = pagination.total;

  if (productsListState.page > pagination.totalPages) {
    productsListState.page = pagination.totalPages;
  }

  updateProductCount(productsListState.totalItems);

  await renderProductsCatalog(
    productsSection,
    data,
    "normal",
    pagination,
    productsListState,
  );

  handlePaginationButtonsColor(productsListState.page);
}
