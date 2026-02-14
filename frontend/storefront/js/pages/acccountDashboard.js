import { fetchOrders_DB } from "../services/ordersServices.js";
import { getSession } from "../services/sessionServices.js";
import { renderOrdersCatalog } from "../components/ordersCatalog.js";

const dashboard = document.querySelector("#user-dashboard") ?? null;
const dashboardSidebar = dashboard?.querySelector("#user-dashboard-sidebar");

export const ordersListState = {
  page: 1,
  perPage: 5,
};

document.addEventListener("DOMContentLoaded", async () => {
  const path = window.location.pathname;
  const sessionData = await getSession();

  if (path.includes("my-account.php") && sessionData.user_id) {
    renderDashboardSection(sessionData);
  }
});

dashboardSidebar?.addEventListener("click", async (e) => {
  const sidebarList = e.target.closest(".sidebar-list");

  if (sidebarList) {
    if (sidebarList.classList.contains("active-subsection")) return;
    const content = document.querySelector("#user-dashboard-content");
    const section = sidebarList.dataset.section;
    changeSidebarSection(dashboardSidebar, sidebarList);
    content.innerHTML = "";

    if (section === "dashboard") {
      const sessionData = await getSession();
      renderDashboardSection(sessionData);
    } else if (section === "orders") {
      // TODO : Load orders function
      await renderOrdersCatalog(content, ordersListState);
    } else if (section === "log-out") {
      window.location.href = "/ecommerce/backend/auth/user_logout.php";
    } else {
      document.querySelector("#user-dashboard-content").innerHTML = "";
    }
  }
});

export async function renderOrdersSection(state) {
  const response = await fetchOrders_DB({
    page: state.page,
    perPage: state.perPage,
  });

  console.log(response);
}

function renderDashboardSection(data) {
  const contentSection = document.querySelector("#user-dashboard-content");
  contentSection.innerHTML = "";

  const firstParagraph = document.createElement("p");
  firstParagraph.classList.add("welcome-text");
  firstParagraph.innerHTML = `Hello <strong>${data.user_name}</span>`;

  const secondParagraph = document.createElement("p");
  secondParagraph.textContent =
    "From your account dashboard you can view your recent orders, maange your shipping addresses, and edit your password and account details";
  secondParagraph.classList.add("dashboard-introduction");

  contentSection.append(firstParagraph, secondParagraph);
}

function changeSidebarSection(sidebar, target) {
  const sidebarLists = sidebar.querySelectorAll(".sidebar-list");

  sidebarLists.forEach((list) => list.classList.remove("active-subsection"));

  target.classList.add("active-subsection");
}
