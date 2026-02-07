import { loadWishlist } from "../pages/wishlistUI.js";

export function createPaginationContainer(pagination, listState) {
  let currentPaginationContainer =
    document.querySelector(".pagination") ?? null;

  if (currentPaginationContainer) currentPaginationContainer.remove();

  if (pagination.totalPages >= 1) {
    const paginationContainer = document.createElement("div");
    paginationContainer.classList.add("pagination");

    const previousPageButton = document.createElement("button");
    previousPageButton.classList.add("pagination-button", "previous");
    previousPageButton.id = "previous-page-button";
    previousPageButton.innerHTML = `
    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    `;

    setPreviousEventListener(previousPageButton, listState);

    const nextPageButton = document.createElement("button");
    nextPageButton.classList.add("pagination-button", "next");
    nextPageButton.innerHTML = `
    <svg class="right-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    `;
    nextPageButton.id = "next-page-button";

    setNextEventListener(nextPageButton, listState);

    const paginationInnerContainer = document.createElement("div");
    paginationInnerContainer.classList.add("pagination-inner-container");

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
}

function setPreviousEventListener(button, state) {
  button.onclick = () => {
    if (state.page > 1) {
      state.page--;
    } else {
      state.page = state.totalPages;
    }
    loadWishlist();
  };
}

function setNextEventListener(button, state) {
  button.onclick = () => {
    if (state.page < state.totalPages) {
      state.page++;
    } else {
      state.page = 1;
    }
    loadWishlist();
  };
}
