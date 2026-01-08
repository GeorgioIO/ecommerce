// Sidebar file
import { changeSidebarSection } from "./helpers.js";
import { loadAuthors } from "./authors/authorsUI.js";
import { loadBooks } from "./books/booksUI.js";
import { loadGenres } from "./genres/genresUI.js";
import { loadCustomers } from "./customers/customerUI.js";

const sidebarButtons = document.querySelectorAll(
  ".sidebar ul li .adm-sidebar-button"
);

sidebarButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    const clickedButton = e.currentTarget;
    const entity = e.currentTarget.dataset.section;
    changeSidebarSection(entity);

    if (clickedButton.dataset.section === "book") {
      loadBooks();
    } else if (clickedButton.dataset.section === "author") {
      loadAuthors();
    } else if (clickedButton.dataset.section === "genre") {
      loadGenres();
    } else if (clickedButton.dataset.section === "customer") {
      loadCustomers();
    }
  });
});
