import { loadProducts } from "../pages/productsPageUI.js";
import { loadWishlist } from "../pages/wishlistUI.js";
let productLoader = null;

export function buildPaginationContainer(type, pagination, listState) {
  if (type === "normal") {
    productLoader = loadProducts;
  } else if (type === "wishlist") {
    productLoader = loadWishlist;
  }

  let currentPaginationContainer =
    document.querySelector(".pagination") ?? null;

  if (currentPaginationContainer) currentPaginationContainer.remove();

  // Pagination container
  const paginationContainer = document.createElement("div");
  paginationContainer.classList.add("pagination");

  // Previous button
  const previousPageButton = document.createElement("button");
  previousPageButton.classList.add("pagination-button", "previous");
  previousPageButton.id = "previous-page-button";
  previousPageButton.innerHTML = `
    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    `;

  setPreviousEventListener(previousPageButton, type, listState);

  // Next button
  const nextPageButton = document.createElement("button");
  nextPageButton.classList.add("pagination-button", "next");
  nextPageButton.innerHTML = `
    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    `;
  nextPageButton.id = "next-page-button";

  setNextEventListener(nextPageButton, type, listState);

  // Handle buttons appearance
  if (pagination.totalPages === 1) {
    previousPageButton.style.display = "none";
    nextPageButton.style.display = "none";
  } else if (pagination.page === 1 && pagination.totalPages > 1) {
    previousPageButton.style.display = "none";
  } else if (pagination.page > 1 && pagination.page === pagination.totalPages) {
    nextPageButton.style.display = "none";
  }

  // Pagination inner contaienr
  const paginationInnerContainer = document.createElement("div");
  paginationInnerContainer.classList.add("pagination-inner-container");

  // Pagination pages button
  for (let i = 1; i <= pagination.totalPages; i++) {
    const paginationPageButton = document.createElement("button");
    paginationPageButton.classList.add("page-button");
    paginationPageButton.textContent = i;
    paginationPageButton.dataset.page = i;
    setPaginationButtonEventListener(
      paginationPageButton,
      type,
      listState,
      pagination,
    );
    paginationInnerContainer.append(paginationPageButton);
  }

  paginationContainer.append(
    previousPageButton,
    paginationInnerContainer,
    nextPageButton,
  );

  return paginationContainer;
}

// Normal pagination buttons
function setPaginationButtonEventListener(button, type, state, pagination) {
  button.onclick = async (e) => {
    const pageButton = parseInt(e.target.dataset.page);
    if (pageButton > state.totalPages || pageButton === state.page) return;
    state.page = pageButton;

    await productLoader();
  };
}

// < Previous pagination button
function setPreviousEventListener(button, type, state) {
  button.onclick = async (e) => {
    if (state.page > 1) {
      state.page--;
    }

    await productLoader();
  };
}

// > Next pagination button
function setNextEventListener(button, type, state) {
  button.onclick = async (e) => {
    if (state.page < state.totalPages) {
      state.page++;
    }

    await productLoader();
  };
}

function handlePaginationButtonsColor(pageNumber) {
  const paginationButtons = document.querySelectorAll(".page-button");

  paginationButtons.forEach((button) =>
    button.classList.remove("active-page-button"),
  );

  const targetPageButton = document.querySelector(
    `.page-button[data-page="${pageNumber}"]`,
  );

  if (!targetPageButton) return;

  targetPageButton.classList.add("active-page-button");
}
