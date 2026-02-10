import { buildPaginationContainer } from "./pagination.js";
import { buildProductCard } from "./productCard.js";
import { getWishlistItems } from "../services/wishlistServices.js";
import { handlePaginationButtonsColor } from "../../../admin/js/UIhelpers.js";
/*

This function is responsible of the idea of building the product catalog its the centralized that makes the product catalog appear in the pages

It has two main sections or heads :
- Products Grid : Load products and display them in .products-grid
- Pagination : Create a pagination for that grid

Input :
- products
- pagination data :
    - page
    - perPage
    - total items
    - total pages

*/
export async function renderProductsCatalog(section, state) {
  const response = await getWishlistItems({
    page: state.page,
    perPage: state.perPage,
  });

  const { data, pagination } = response;

  state.totalPages = pagination.totalPages;
  state.totalItems = pagination.total;

  if (data.length > 0) {
    const newCatalog = await buildProductsCatalog(data, pagination, state);

    const oldCatalog = document.querySelector(".products-catalog");

    if (oldCatalog) {
      oldCatalog.replaceWith(newCatalog);
    } else {
      section.append(newCatalog);
    }

    handlePaginationButtonsColor(state.page);
  } else {
    const currentCatalog = document.querySelector(".products-catalog");
    if (currentCatalog) currentCatalog.remove();

    const emptyContainer = document.createElement("div");
    emptyContainer.classList.add("empty-container");

    const emptyText = document.createElement("p");
    emptyText.innerHTML = `You don't have any product <a href="">Click here to add</a>`;

    emptyContainer.append(emptyText);

    section.append(emptyContainer);
  }
}

export async function buildProductsCatalog(products, pagination, state) {
  // Create product catalog = the main element that will be returned
  const productCatalog = document.createElement("div");
  productCatalog.classList.add("products-catalog");

  // Products grid
  const grid = buildProductGrid(products);

  // Pagination Container
  const paginationContainer = buildPaginationContainer(pagination, state);

  productCatalog.append(grid, paginationContainer);

  return productCatalog;
}

function buildProductGrid(products) {
  const productsGrid = document.createElement("div");
  productsGrid.classList.add("products-grid");

  products.forEach((product) => {
    const productCard = buildProductCard(product, "wishlist");
    productsGrid.append(productCard);
  });

  return productsGrid;
}
