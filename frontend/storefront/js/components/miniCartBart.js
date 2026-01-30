import { swapClass } from "../../../admin/js/UIhelpers.js";

export function BuildMiniCartBar() {
  let miniCartMenu = document.querySelector("#mini-cart-bar") ?? null;

  if (miniCartMenu) {
    miniCartMenu.remove();
  }

  miniCartMenu = document.createElement("div");
  miniCartMenu.id = "mini-cart-bar";

  // Header
  const miniCartMenuHeader = document.createElement("header");
  miniCartMenuHeader.classList.add("mini-cart-bar-header");

  const miniCartMenuTitle = document.createElement("h2");
  miniCartMenuTitle.classList.add("mini-cart-bar-title");
  miniCartMenuTitle.textContent = "CART";

  const closeMiniCartMenu = document.createElement("button");
  closeMiniCartMenu.id = "close-mini-cart-button";
  closeMiniCartMenu.type = "button";
  closeMiniCartMenu.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
    </svg>
  `;

  miniCartMenuHeader.append(miniCartMenuTitle, closeMiniCartMenu);

  closeMiniCartMenu.onclick = function () {
    swapClass(miniCartMenu, "slide-out-right", "slide-in-right");
  };

  miniCartMenu.append(miniCartMenuHeader);

  return miniCartMenu;
}
