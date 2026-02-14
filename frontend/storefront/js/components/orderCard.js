// This function is responsible of building a single order card
// Input : Order data (id , title...)
// Output : Order card element

import { fetchOrderLines_DB } from "../services/ordersServices.js";

export async function buildOrderCard(order) {
  // Create order card
  const orderCard = document.createElement("div");
  orderCard.classList.add("order-card");
  orderCard.dataset.orderid = order.id;

  // Order card information
  const orderCardInfoContainer = document.createElement("div");
  orderCardInfoContainer.classList.add("order-card-information-container");

  // Create order data rows
  Object.keys(order).forEach((key) => {
    if (key != "id" && key != "date_added") {
      const orderInfoRow = document.createElement("div");
      orderInfoRow.classList.add("order-info-row");

      const orderInfoTitle = document.createElement("div");
      orderInfoTitle.classList.add("order-info-title");
      orderInfoTitle.innerHTML = `<h4> ${key} </h4>`;

      const orderInfoData = document.createElement("div");
      orderInfoData.classList.add("order-info-data");
      if (key === "Total Price") {
        orderInfoData.innerHTML = `<p> $${order[key]} </p>`;
      } else {
        orderInfoData.innerHTML = `<p> ${order[key]} </p>`;
      }

      orderInfoRow.append(orderInfoTitle, orderInfoData);

      orderCardInfoContainer.append(orderInfoRow);
    }
  });

  // Create order card footer
  const orderCardFooter = document.createElement("div");
  orderCardFooter.classList.add("order-card-footer");

  const viewDetailsButton = document.createElement("button");
  viewDetailsButton.classList.add("view-order-details-button");
  viewDetailsButton.type = "button";
  viewDetailsButton.textContent = "View Details";

  viewDetailsButton.onclick = async (e) => {
    // Show order lines
    // await setViewDetailsEventListener(order);
  };

  orderCardFooter.append(viewDetailsButton);

  orderCard.append(orderCardInfoContainer, orderCardFooter);

  return orderCard;
}

function buildOrderLinesContainer(lines) {
  const orderLinesContainer = document.createElement("div");
  orderLinesContainer.classList.add("order-lines-container");

  lines.forEach((line) => {
    const orderLine = document.createElement("div");
    orderLine.classList.add("order-line");

    Object.keys(line).forEach((key) => {
      if (key != "book_id") {
        const cell = document.createElement("div");
        cell.innerHTML = `<p> ${line[key]} </p>`;

        orderLine.append(cell);
      }
    });

    orderLinesContainer.append(orderLine);
  });

  return orderLinesContainer;
}

async function setViewDetailsEventListener(order) {
  const lines = await fetchOrderLines_DB(order.id);
  const linesContainer = buildOrderLinesContainer(lines);
  linesContainer.style.display = "flex";
  document.body.append(linesContainer);
}
