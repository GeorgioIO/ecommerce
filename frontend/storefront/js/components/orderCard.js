// This function is responsible of building a single order card
// Input : Order data (id , title...)
// Output : Order card element

import { swapClass } from "../../../admin/js/UIhelpers.js";
import { getOrderLines_DB } from "../services/ordersServices.js";

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
    await setViewDetailsEventListener(order);
  };

  orderCardFooter.append(viewDetailsButton);

  orderCard.append(orderCardInfoContainer, orderCardFooter);

  return orderCard;
}

function buildOrderLinesContainer(lines) {
  let orderLinesContainer = document.querySelector(".order-lines-container");
  if (orderLinesContainer) orderLinesContainer.remove();

  orderLinesContainer = document.createElement("div");
  orderLinesContainer.classList.add("order-lines-container");

  // Build Order lines header
  const orderLinesHeader = document.createElement("div");
  orderLinesHeader.classList.add("order-lines-container-header");

  const orderLinesHeaderTitle = document.createElement("h2");
  orderLinesHeaderTitle.classList.add("order-lines-container-title");
  orderLinesHeaderTitle.textContent = "Order Lines";

  const orderLinesContainerCloseButton = document.createElement("button");
  orderLinesContainerCloseButton.type = "button";
  orderLinesContainerCloseButton.id = "close-order-lines-button";
  orderLinesContainerCloseButton.innerHTML = `
  <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="-0.5 0 25 25">
    <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
  </svg>
  `;

  orderLinesContainerCloseButton.onclick = () => {
    swapClass(orderLinesContainer, "slide-out-right", "slide-in-right");
  };

  orderLinesHeader.append(
    orderLinesHeaderTitle,
    orderLinesContainerCloseButton,
  );

  orderLinesContainer.append(orderLinesHeader);

  lines.forEach((line) => {
    const orderLine = document.createElement("div");
    orderLine.classList.add("order-line");

    Object.keys(line).forEach((key) => {
      if (key === "book_id" || key === "title") return;
      const cell = document.createElement("div");

      if (key === "cover_image") {
        cell.classList.add("image-cell");
        const figure = document.createElement("figure");
        const image = document.createElement("img");
        image.src = "../../../assets/images/" + line[key];
        image.alt = `${line["title"]} image`;

        figure.append(image);
        cell.append(figure);
      } else if (key.includes("price")) {
        cell.innerHTML = `<p> $${line[key]} </p>`;
      } else {
        cell.innerHTML = `<p> ${line[key]} </p>`;
      }

      orderLine.append(cell);
    });

    orderLinesContainer.append(orderLine);
  });

  return orderLinesContainer;
}

async function setViewDetailsEventListener(order) {
  const lines = await getOrderLines_DB(order.id);
  const linesContainer = buildOrderLinesContainer(lines.data);
  swapClass(linesContainer, "slide-in-right", "slide-out-right");
  document.body.append(linesContainer);
}
