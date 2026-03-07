import { buildPaginationContainer } from "./pagination.js";
import { buildProductCard } from "./productCard.js";
import { swapClass } from "../../../admin/js/UIhelpers.js";
import { buildFilteringBar } from "./filteringBar/filteringBar.js";
import { populateFilteringBar } from "./filteringBar/filteringBarPopulator.js";
import { currentFilters } from "../core/currentFilters.js";
import { populateExistingFilters } from "./filteringBar/filteringBarPopulator.js";

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

const showFilterBarButton =
  document.querySelector("#show-filtering-bar-button") ?? null;

showFilterBarButton?.addEventListener("click", async () => {
  const filteringBar = buildFilteringBar();

  document.body.append(filteringBar);

  // Populate the form with selects data
  await populateFilteringBar();

  // Populate filter with already existing filters
  populateExistingFilters(currentFilters);

  swapClass(filteringBar, "slide-in-right", "slide-out-right");
});

export async function renderProductsCatalog(
  section,
  data,
  dataType,
  paginationData,
  listingState,
) {
  // Catalog is not empty
  if (data.length > 0) {
    const emptyContainer = document.querySelector(".empty-container") ?? null;
    if (emptyContainer) emptyContainer.remove();

    const newCatalog = await buildProductsCatalog(
      data,
      dataType,
      paginationData,
      listingState,
    );
    const oldCatalog = document.querySelector(".products-catalog");

    if (oldCatalog) {
      oldCatalog.replaceWith(newCatalog);
    } else {
      section.append(newCatalog);
    }
  }
  // Catalog is empty
  else {
    const currentCatalog = document.querySelector(".products-catalog");
    if (currentCatalog) currentCatalog.remove();

    const currentEmptyContainer =
      document.querySelector(".empty-container") ?? null;
    if (currentEmptyContainer) currentEmptyContainer.remove();

    const emptyContainer = document.createElement("div");
    emptyContainer.classList.add("empty-container");

    const emptyText = document.createElement("p");
    emptyText.innerHTML = `No products`;

    emptyContainer.append(emptyText);

    section.append(emptyContainer);
  }
}

export async function buildProductsCatalog(
  products,
  productsType,
  pagination,
  state,
) {
  // Create product catalog = the main element that will be returned
  const productCatalog = document.createElement("div");
  productCatalog.classList.add("products-catalog");

  // Products grid
  const grid = buildProductGrid(products, productsType);

  // Pagination Container
  const paginationContainer = buildPaginationContainer(
    productsType,
    pagination,
    state,
  );

  productCatalog.append(grid, paginationContainer);

  return productCatalog;
}

function buildProductGrid(products, productsType) {
  const productsGrid = document.createElement("div");
  productsGrid.classList.add("products-grid");

  products.forEach(async (product) => {
    const productCard = await buildProductCard(product, productsType);
    productsGrid.append(productCard);
  });

  return productsGrid;
}
