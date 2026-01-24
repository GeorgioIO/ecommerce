import { renderMiniActiveTableState } from "../UIhelpers.js";
import { buildDashboardSkeleton } from "./dashboardBuilder.js";
import {
  getAdminSession,
  loadDashboardKPIs_DB,
  loadLastFiveOrders_DB,
  loadMostSellingGenres_DB,
  loadValuableCustomers_DB,
} from "./dashboardServices.js";
import {
  renderMiniOrdersTableHeader,
  renderMiniOrdersTableRow,
} from "./miniOrdersTable/miniOrdersTableUI.js";
import {
  loadHorizontalBarChart,
  loadPieChart,
} from "./charts/chartsFunctions.js";
import { loadAdminNotifications_DB } from "../notifications/notificationsServices.js";
import {
  changeNotificationCountBadge,
  loadNotifications,
} from "../notifications/notificationUI.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

const kpiCardsConfigs = {
  total_orders: "Total Orders",
  total_customers: "Total Customers",
  total_revenue: "Total Revenue",
  total_pending_orders: "Pending Orders",
  total_ofs_books: "Out of Stock Books",
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

function loadRightUpperGraph() {
  const upperGraph = document.createElement("div");
  upperGraph.id = "right-upper-graph";

  return upperGraph;
}

function loadRightLowerGraph() {
  const lowerGraph = document.createElement("div");
  lowerGraph.id = "right-lower-graph";

  return lowerGraph;
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

  // Load Valuable Customers
  const valuableCustomers = await loadValuableCustomers_DB();

  const customerNames = valuableCustomers.map((item) => item.name);
  const revenuesPerCustomer = valuableCustomers.map(
    (item) => item.total_orders_revenue,
  );

  const rightUpperGraph = loadRightUpperGraph();
  dashboardContent.append(rightUpperGraph);

  loadHorizontalBarChart(
    rightUpperGraph.id,
    revenuesPerCustomer,
    customerNames,
    "Most Valuable Customers",
  );

  const mostSellingGenres = await loadMostSellingGenres_DB();

  const genreNames = mostSellingGenres.map((item) => item.name);
  const revenuesPerGenre = mostSellingGenres.map(
    (item) => item.total_orders_revenue,
  );

  const rightLowerGraph = loadRightLowerGraph();
  dashboardContent.append(rightLowerGraph);

  loadPieChart(
    rightLowerGraph.id,
    genreNames,
    revenuesPerGenre,
    "Revenue By Genres",
  );

  loadNotifications();
  changeNotificationCountBadge();
}
