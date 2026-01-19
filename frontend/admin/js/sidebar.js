// Sidebar file
import { changeSidebarSection } from "./UIhelpers.js";
import { loadAuthors } from "./authors/authorsUI.js";
import { loadBooks } from "./books/booksUI.js";
import { loadGenres } from "./genres/genresUI.js";
import { loadCustomers } from "./customers/customerUI.js";
import { loadOrders } from "./orders/orderUI.js";

const sidebarButtons = document.querySelectorAll(
  ".sidebar ul li .adm-sidebar-button"
);

sidebarButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    const clickedButton = e.currentTarget;
    const section = e.currentTarget.dataset.section;
    changeSidebarSection(section);

    if (section === "book") {
      loadBooks();
    } else if (section === "author") {
      loadAuthors();
    } else if (section === "genre") {
      loadGenres();
    } else if (section === "customer") {
      loadCustomers();
    } else if (section === "order") {
      loadOrders();
    } else if (section === "logout") {
      window.location.href = "/ecommerce/backend/auth/admin_logout.php";
    }
  });
});
