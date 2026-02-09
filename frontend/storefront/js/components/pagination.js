import { renderProductsCatalog } from "./productsCatalog.js";

export function buildPaginationContainer(pagination, listState) {
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

  setPreviousEventListener(previousPageButton, listState);

  // Next button
  const nextPageButton = document.createElement("button");
  nextPageButton.classList.add("pagination-button", "next");
  nextPageButton.innerHTML = `
    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    `;
  nextPageButton.id = "next-page-button";

  setNextEventListener(nextPageButton, listState);

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
    paginationInnerContainer.append(paginationPageButton);
  }

  paginationContainer.append(
    previousPageButton,
    paginationInnerContainer,
    nextPageButton,
  );

  return paginationContainer;
}

function setPreviousEventListener(button, state) {
  button.onclick = (e) => {
    if (state.page > 1) {
      state.page--;
    }

    const closestSection = e.target.closest("section");
    renderProductsCatalog(closestSection, state);
  };
}

function setNextEventListener(button, state) {
  button.onclick = (e) => {
    console.log(state);
    if (state.page < state.totalPages) {
      state.page++;
    }

    const closestSection = e.target.closest("section");
    renderProductsCatalog(closestSection, state);
  };
}
