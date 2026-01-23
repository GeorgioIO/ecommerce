import { renderMiniActiveTableState } from "../UIhelpers.js";
import { buildDashboardSkeleton } from "./dashboardBuilder.js";
import {
  getAdminSession,
  loadDashboardKPIs_DB,
  loadLastFiveOrders_DB,
} from "./dashboardServices.js";
import {
  renderMiniOrdersTableHeader,
  renderMiniOrdersTableRow,
} from "./miniOrdersTable/miniOrdersTableUI.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

const kpiCardsConfigs = {
  total_orders: "Total Orders",
  total_customers: "Total Customers",
  total_revenue: "Total Revenue",
  total_pending_orders: "Total Pending Orders",
  total_ofs_books: "Total OFS Books",
};

// ========== LISTENERS ========== //

// ========== LOCAL FUNCTIONS ========== //
function loadKPISCards(data) {
  const dashboardKPISContainer = content.querySelector(".dashboard-kpis");

  Object.keys(data.value).forEach((kpi) => {
    const kpiCard = document.createElement("div");
    kpiCard.classList.add("kpi-card");

    const kpiTitle = document.createElement("h3");
    kpiTitle.classList.add("kpi-title");
    kpiTitle.textContent = kpiCardsConfigs[kpi];

    const kpiContent = document.createElement("p");
    kpiContent.classList.add("kpi-content");

    if (kpi === "total_revenue") {
      kpiContent.innerHTML = `$${data.value[kpi]}`;
    } else {
      kpiContent.innerHTML = data.value[kpi];
    }

    kpiCard.append(kpiTitle, kpiContent);

    dashboardKPISContainer.append(kpiCard);
  });
}

function loadMiniOrdersTable(data) {
  const rectangle = document.createElement("div");
  rectangle.id = "rectangle-graph";

  const miniTable = renderMiniActiveTableState(
    data,
    renderMiniOrdersTableHeader,
    renderMiniOrdersTableRow,
  );

  rectangle.innerHTML = miniTable;
  return rectangle;
}

// ========== EXPORTED FUNCTIONS ========== //
export async function loadDashboard() {
  content.innerHTML = "";

  // Build dashboard skeleton
  const adminDashboard = buildDashboardSkeleton();
  const adminDashboardMain = adminDashboard.querySelector(".dashboard-main");

  // Load admin name
  const adminSession = await getAdminSession();
  const headerSubtitle = adminDashboard.querySelector(".dashboard-subtitle");
  headerSubtitle.textContent = `Welcome ${adminSession.name} 👋`;

  content.append(adminDashboard);

  const dashboardContent = content.querySelector(".dashboard-content");

  // Load KPIs Cards
  const kpisData = await loadDashboardKPIs_DB();

  loadKPISCards(kpisData);

  // Load Recent Orders
  const recentOrders = await loadLastFiveOrders_DB();

  const recentOrdersGraph = loadMiniOrdersTable(recentOrders);

  dashboardContent.append(recentOrdersGraph);
}
