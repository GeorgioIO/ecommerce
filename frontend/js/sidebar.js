// Sidebar file

import { loadAuthors } from "./authors/authorsUI.js";
import { loadBooks } from "./books/booksUI.js";

var sidebarButtons = document.querySelectorAll(
  ".sidebar ul li .adm-sidebar-button"
);

sidebarButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    const clickedButton = e.currentTarget;
    sidebarButtons.forEach((button) => {
      button.classList.remove("active-sidebar-btn");
      button.querySelector("p").classList.remove("active-sidebar-text");
    });
    clickedButton.classList.add("active-sidebar-btn");
    clickedButton.querySelector("p").classList.add("active-sidebar-text");
    // content.innerHTML = temporary_contents[event.currentTarget.dataset.section];

    if (clickedButton.dataset.section === "products") {
      loadBooks();
    } else if (clickedButton.dataset.section === "authors") {
      loadAuthors();
    }
  });
});
