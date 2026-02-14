import { fetchOrders_DB } from "../services/ordersServices.js";
import { buildPaginationContainer } from "./pagination.js";
import { buildOrderCard } from "./orderCard.js";
import { handlePaginationButtonsColor } from "../../../admin/js/UIhelpers.js";

export async function renderOrdersCatalog(section, state) {
  // Get orders for the user
  const response = await fetchOrders_DB({
    page: state.page,
    perPage: state.perPage,
  });

  const { data, pagination } = response;

  // Set pagination data
  state.totalPages = pagination.totalPages;
  state.totalItems = pagination.total;

  // Available data
  if (data.length > 0) {
    const newOrderCatalog = await buildOrdersCatalog(
      data,
      pagination,
      state,
      renderOrdersCatalog,
    );

    const oldCatalog = document.querySelector(".orders-catalog");

    if (oldCatalog) {
      oldCatalog.replaceWith(newOrderCatalog);
    } else {
      section.append(newOrderCatalog);
    }

    handlePaginationButtonsColor(state.page);
  } else {
    // if empty
    console.log("test");
  }
}

export async function buildOrdersCatalog(
  orders,
  pagination,
  state,
  renderFunction,
) {
  // Orders catalog = the item that will be return
  const ordersCatalog = document.createElement("div");
  ordersCatalog.classList.add("orders-catalog");

  // Orders grid
  const grid = buildOrdersGrid(orders);

  // Pagination container
  const paginationContainer = buildPaginationContainer(
    pagination,
    state,
    renderFunction,
  );

  ordersCatalog.append(grid, paginationContainer);

  return ordersCatalog;
}

function buildOrdersGrid(orders) {
  const ordersGrid = document.createElement("div");
  ordersGrid.classList.add("orders-grid");

  orders.forEach(async (order) => {
    const orderCard = await buildOrderCard(order);
    ordersGrid.append(orderCard);
  });

  return ordersGrid;
}
