import { getSession } from "../services/sessionServices.js";

const dashboard = document.querySelector("#user-dashboard") ?? null;
const dashboardSidebar = dashboard?.querySelector("#user-dashboard-sidebar");

document.addEventListener("DOMContentLoaded", async () => {
  const path = window.location.pathname;
  const sessionData = await getSession();

  if (path.includes("my-account.php") && sessionData.user_id) {
    loadDashboardSection(sessionData);
  }
});

dashboardSidebar?.addEventListener("click", async (e) => {
  const sidebarList = e.target.closest(".sidebar-list");

  if (sidebarList) {
    if (sidebarList.classList.contains("active-subsection")) return;
    const section = sidebarList.dataset.section;
    console.log(section);
    changeSidebarSection(dashboardSidebar, sidebarList);

    if (section === "dashboard") {
      const sessionData = await getSession();
      loadDashboardSection(sessionData);
    } else if (section === "orders") {
    } else {
      document.querySelector("#user-dashboard-content").innerHTML = "";
    }
  }
});

function loadDashboardSection(data) {
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
