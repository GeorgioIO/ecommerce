import { swapClass } from "../../../admin/js/UIhelpers.js";

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
  const searchBarSearchingContainer = document.createElement("div");
  searchBarSearchingContainer.classList.add("search-bar-searching-container");

  const searchInput = document.createElement("input");
  searchInput.classList.add("search-input");
  searchInput.id = "search";
  searchInput.autocomplete = "off";
  searchInput.placeholder = "Type a keyword..";

  searchBarSearchingContainer.append(searchInput);

  searchBar.append(searchBarHeader, searchBarSearchingContainer);

  return searchBar;
}
