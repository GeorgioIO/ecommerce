import { swapClass } from "../../../admin/js/UIhelpers.js";
import { searchBooks } from "../services/booksServices.js";
import { buildSidebarCard } from "./sidebarProductCard.js";
let maxMiniSearch = 4;
let searchCounter = 0;
export function buildSearchBar() {
  let searchBar = document.querySelector("#search-bar") ?? null;

  if (searchBar) {
    searchBar.remove();
  }

  searchBar = document.createElement("div");
  searchBar.id = "search-bar";

  // Header
  const searchBarHeader = document.createElement("header");
  searchBarHeader.classList.add("search-bar-header");

  const searchBarTitle = document.createElement("h2");
  searchBarTitle.classList.add("search-bar-title");
  searchBarTitle.textContent = "SEARCH";

  const closeSearchBarButton = document.createElement("button");
  closeSearchBarButton.id = "close-searchbar-button";
  closeSearchBarButton.type = "button";
  closeSearchBarButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
    </svg>
  `;

  searchBarHeader.append(searchBarTitle, closeSearchBarButton);

  closeSearchBarButton.onclick = function () {
    swapClass(searchBar, "slide-out-right", "slide-in-right");
  };

  // Field
  const searchBarActionContainer = document.createElement("div");
  searchBarActionContainer.classList.add("search-bar-action-container");

  const searchInput = document.createElement("input");
  searchInput.classList.add("search-input");
  searchInput.id = "search";
  searchInput.autocomplete = "off";
  searchInput.placeholder = "Type a keyword..";

  const searchBarSearchContainer = document.createElement("div");
  searchBarSearchContainer.classList.add("search-bar-search-container");

  const title = document.createElement("h5");
  title.classList.add("search-title");
  title.textContent = "Products";

  const searchResultContainer = document.createElement("div");
  searchResultContainer.classList.add("search-result-container");

  const searchMoreButton = document.createElement("button");
  searchMoreButton.classList.add("view-more-button");
  searchMoreButton.textContent = `Search`;

  searchBarSearchContainer.append(
    title,
    searchResultContainer,
    searchMoreButton,
  );

  let lastQuery = "";

  searchInput.addEventListener("input", async () => {
    let searchValue = searchInput.value.trim();

    if (searchValue.length === 0) {
      searchMoreButton.textContent = `Search`;

      searchBarSearchContainer.style.display = "none";
    }

    searchMoreButton.textContent = `Search more for "${searchValue}"`;

    lastQuery = searchValue;
    searchResultContainer.innerHTML = "";
    searchCounter = 0;

    if (searchValue.length <= 2) return;

    searchBarSearchContainer.style.display = "flex";

    try {
      const books = await searchBooks(searchValue);

      if (searchValue !== lastQuery || !books?.length) return;

      for (const book of books) {
        if (searchCounter >= maxMiniSearch) break;
        searchResultContainer.append(buildSidebarCard(book, "search"));
        searchCounter++;
      }
    } catch (err) {
      console.log("Search failed");
    }
  });

  searchBarActionContainer.append(searchInput);

  searchBar.append(
    searchBarHeader,
    searchBarActionContainer,
    searchBarSearchContainer,
  );

  return searchBar;
}
