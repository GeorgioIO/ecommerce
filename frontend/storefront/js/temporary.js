import { swapClass } from "../../admin/js/UIhelpers.js";

const hamburgerMenu = document.querySelector(".hamburger-menu");
const closeSidebar = document.querySelector("#close-sidebar-button");

hamburgerMenu.addEventListener("click", () => {
  const sidebar = document.querySelector("#site-sidebar");

  swapClass(sidebar, "slide-in-sidebar", "slide-out-sidebar");
});

closeSidebar.addEventListener("click", () => {
  const sidebar = document.querySelector("#site-sidebar");

  swapClass(sidebar, "slide-out-sidebar", "slide-in-sidebar");
});
