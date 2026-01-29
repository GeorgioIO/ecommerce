import { swapClass } from "../../admin/js/UIhelpers.js";
import {
  buildCollectionsBar,
  populateCollectionsBar,
} from "./components/collectionsBar.js";
import { getGenres_DB } from "./services/booksServices.js";

const sidebar = document.querySelector("#site-sidebar");
const hamburgerMenu = document.querySelector(".hamburger-menu");

sidebar.addEventListener("click", async (e) => {
  const closeSidebarButton = e.target.closest("#close-sidebar-button");
  const closeCollectionContainer = e.target.closest(".close-collection-li");
  const openCollectionContainer = e.target.closest(".sidebar-collection-li");

  if (closeSidebarButton) {
    swapClass(sidebar, "slide-out-sidebar", "slide-in-sidebar");
  }

  if (closeCollectionContainer) {
    const genresContainer = sidebar.querySelector(".genres-sidebar-container");

    swapClass(
      genresContainer,
      "slide-out-genres-sidebar",
      "slide-in-genres-sidebar",
    );

    setTimeout(() => {
      if (genresContainer) {
        genresContainer.remove();
      }
    }, 400);
  }

  if (openCollectionContainer) {
    const genres = await getGenres_DB();

    const genresContainer = buildCollectionsBar();

    populateCollectionsBar(genresContainer, genres);

    sidebar.append(genresContainer);

    swapClass(
      genresContainer,
      "slide-in-genres-sidebar",
      "slide-out-genres-sidebar",
    );
  }
});

// Open sidebar
hamburgerMenu.addEventListener("click", () => {
  swapClass(sidebar, "slide-in-sidebar", "slide-out-sidebar");
});
