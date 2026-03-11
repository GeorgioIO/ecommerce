import { buildPaginationContainer } from "./pagination.js";
import { buildOrderCard } from "./orderCard.js";

export async function renderOrdersCatalog(
  section,
  state,
  data,
  type,
  pagination,
) {
  // Available data
  if (data.length > 0) {
    const newOrderCatalog = await buildOrdersCatalog(
      data,
      type,
      pagination,
      state,
    );

    const oldCatalog = document.querySelector(".orders-catalog");

    if (oldCatalog) {
      oldCatalog.replaceWith(newOrderCatalog);
    } else {
      section.append(newOrderCatalog);
    }
  } else {
    // if empty
    const emptyText = document.createElement("p");
    emptyText.classList.add("empty-orders-catalog-text");
    emptyText.textContent = "No current orders yet";

    section.append(emptyText);
  }
}

export async function buildOrdersCatalog(orders, type, pagination, state) {
  // Orders catalog = the item that will be return
  const ordersCatalog = document.createElement("div");
  ordersCatalog.classList.add("orders-catalog");

  // Orders grid
  const grid = buildOrdersGrid(orders);

  // Pagination container
  const paginationContainer = buildPaginationContainer(type, pagination, state);

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
