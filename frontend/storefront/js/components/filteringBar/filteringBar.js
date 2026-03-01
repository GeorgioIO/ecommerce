import { swapClass } from "../../../../admin/js/UIhelpers.js";
import { buildFilteringForm } from "./filteringFormBuilder.js";

export function buildFilteringBar() {
  let filteringBar = document.querySelector("#filtering-bar") ?? null;

  if (filteringBar) {
    filteringBar.remove();
  }

  filteringBar = document.createElement("div");
  filteringBar.id = "filtering-bar";

  // Header
  const filteringBarHeader = document.createElement("header");
  filteringBarHeader.classList.add("filtering-bar-header");

  const filteringBarTitle = document.createElement("h2");
  filteringBarTitle.classList.add("filtering-bar-title");
  filteringBarTitle.textContent = "Filter and Sort";

  const closeFilteringBarButton = document.createElement("button");
  closeFilteringBarButton.id = "close-filtering-bar-button";
  closeFilteringBarButton.type = "button";
  closeFilteringBarButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
    </svg>
  `;

  filteringBarHeader.append(filteringBarTitle, closeFilteringBarButton);

  closeFilteringBarButton.onclick = function () {
    swapClass(filteringBar, "slide-out-right", "slide-in-right");
  };

  const filteringForm = buildFilteringForm();

  filteringBar.append(filteringBarHeader, filteringForm);

  return filteringBar;
}
