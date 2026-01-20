import { orderLineTableHeaders } from "./orderLinesTableConfigs.js";
import { showProductSearch } from "./orderLineSearch.js";
import { normalizeOrderLineData } from "../helpers.js";
import { swapClass } from "../UIhelpers.js";

const formContainer = document.querySelector(".form-container");

// LISTENERS

formContainer.addEventListener("click", (e) => {
  const addNewLineButton = e.target.closest("#add-new-order-line-button");
  const deleteLineButton = e.target.closest("#delete-order-line-button");
  const addNewBookButton = e.target.closest("#add-book-line-button");

  // Append new order line
  if (addNewLineButton) {
    const newLine = createOrderLine();
    appendOrderLine(newLine);
  }

  // Delete order line
  if (deleteLineButton) {
    deleteOrderLineRow(deleteLineButton);
  }

  // Add Book is clicked
  if (addNewBookButton) {
    const cell = addNewBookButton.closest(".order-lines-table-line-cell");
    showProductSearch(cell);
  }
});

// ========== EXPORTED FUNCTIONS ========== //

export function hydrateOrderLinesTable(
  orderLines,
  mode = null,
  orderStatus = null,
) {
  orderLines.forEach((line) => {
    let orderLineElement = createOrderLine(mode, orderStatus);
    appendOrderLine(orderLineElement);
    hydrateProductLine(line, orderLineElement, mode, orderStatus);
  });
}

export function buildOrderLinesSection() {
  // Create section
  const orderLinesSection = document.createElement("section");
  orderLinesSection.classList.add("order-form-section");

  // Create title
  const orderLinesSectionTitle = document.createElement("h3");
  orderLinesSectionTitle.textContent = "Order Lines : ";
  orderLinesSectionTitle.classList.add("order-form-section-title");

  // Append title
  orderLinesSection.append(orderLinesSectionTitle);

  const headers = orderLineTableHeaders.headers;

  // Create the table
  const orderLinesTable = document.createElement("div");
  orderLinesTable.classList.add("order-lines-table");

  // Create header
  const orderLinesTbHeader = document.createElement("div");
  orderLinesTbHeader.classList.add("order-lines-table-header");

  // Create the headers row
  headers.forEach((headerElement) => {
    const tableHeaderElement = document.createElement("div");
    tableHeaderElement.classList.add("order-lines-table-header-element");
    tableHeaderElement.innerHTML =
      headerElement.icon === undefined
        ? headerElement.headerTitle
        : headerElement.icon;
    orderLinesTbHeader.append(tableHeaderElement);
  });

  orderLinesTable.append(orderLinesTbHeader);

  // Create the table body
  const orderLinesTbBody = document.createElement("div");
  orderLinesTbBody.classList.add("order-lines-table-body");

  // Create the add new line button
  const addNewLineButton = document.createElement("button");
  addNewLineButton.id = "add-new-order-line-button";
  addNewLineButton.textContent = "Add new line";
  addNewLineButton.type = "button";
  orderLinesTbBody.append(addNewLineButton);

  orderLinesTable.append(orderLinesTbBody);

  orderLinesSection.append(orderLinesTable);

  return orderLinesSection;
}

// Clear order line table
export function clearOrderLineTable() {
  const orderLines = formContainer.querySelectorAll(".order-lines-table-line");
  orderLines.forEach((line) => line.remove());
}

// Append line to table
export function appendOrderLine(line) {
  const addNewLineButton = formContainer.querySelector(
    "#add-new-order-line-button",
  );
  const orderLineTbBody = formContainer.querySelector(
    ".order-lines-table-body",
  );

  orderLineTbBody.insertBefore(line, addNewLineButton);
}

// Create the DOM of an order line
export function createOrderLine(mode = null, status = null) {
  // Create line
  const orderLineContainer = document.createElement("div");
  orderLineContainer.classList.add("order-lines-table-line");

  const bookCell = document.createElement("div");
  bookCell.classList.add("order-lines-table-line-cell");
  bookCell.classList.add("book-cell");

  const addBookButton = document.createElement("button");
  addBookButton.type = "button";
  addBookButton.id = "add-book-line-button";
  addBookButton.textContent = "+";

  bookCell.append(addBookButton);

  const quantityCell = document.createElement("div");
  quantityCell.classList.add("order-lines-table-line-cell");
  quantityCell.classList.add("quantity-cell");

  const quantityInput = document.createElement("input");
  quantityInput.type = "number";
  quantityInput.value = 1;
  quantityInput.step = 1;
  quantityInput.min = 1;
  quantityInput.name = "order-line-quantity";
  quantityInput.classList.add("quantity-line-input");

  quantityCell.append(quantityInput);

  const unitPriceCell = document.createElement("div");
  unitPriceCell.classList.add("order-lines-table-line-cell");
  unitPriceCell.classList.add("unit-price-cell");
  unitPriceCell.textContent = "$0";

  const totalLinePriceCell = document.createElement("div");
  totalLinePriceCell.classList.add("order-lines-table-line-cell");
  totalLinePriceCell.classList.add("total-line-price-cell");
  totalLinePriceCell.textContent = "$0";

  const deleteLineCell = document.createElement("div");
  deleteLineCell.classList.add("order-lines-table-line-cell");
  deleteLineCell.classList.add("delete-line-cell");

  const deleteLineButton = document.createElement("button");
  deleteLineButton.id = "delete-order-line-button";
  deleteLineButton.type = "button";
  deleteLineButton.textContent = "X";
  if (mode === "edit" && status !== "Pending" && status !== "Processing") {
    deleteLineButton.disabled = true;
  }

  deleteLineCell.append(deleteLineButton);

  orderLineContainer.append(
    bookCell,
    quantityCell,
    unitPriceCell,
    totalLinePriceCell,
    deleteLineCell,
  );

  return orderLineContainer;
}

// Delete the DOM of an order line
export function deleteOrderLineRow(clickedbutton) {
  if (!clickedbutton) return;

  const line = clickedbutton.closest(".order-lines-table-line");
  if (!line) return;

  line.remove();

  handleTotalOrderLinesPrice();
}

// Hydrate an order line
export function hydrateProductLine(rawData, row, mode, status) {
  if (!row) return;

  const lineData = normalizeOrderLineData(rawData);
  row.dataset.bookid = lineData.bookId;

  const quantityInput = row.querySelector(".quantity-cell input");
  quantityInput.value = lineData.quantity;
  if (mode === "edit" && status !== "Pending" && status !== "Processing") {
    quantityInput.readOnly = true;
  }

  const total = lineData.unitPrice * lineData.quantity;

  row.querySelector(".book-cell").textContent = lineData.title;

  const priceCell = row.querySelector(".unit-price-cell");
  priceCell.textContent = `$${lineData.unitPrice.toFixed(2)}`;
  priceCell.dataset.value = lineData.unitPrice.toFixed(2);

  const totalCell = row.querySelector(".total-line-price-cell");
  totalCell.textContent = `$${total.toFixed(2)}`;
  totalCell.dataset.value = total.toFixed(2);
}

export function handleOrderLinePriceChange(line) {
  const inlineQuantityNumber = line.querySelector(".quantity-line-input");
  const unitPrice = line.querySelector(".unit-price-cell");
  const totalPrice = line.querySelector(".total-line-price-cell");
  const total =
    parseInt(inlineQuantityNumber.value) * parseFloat(unitPrice.dataset.value);
  totalPrice.textContent = `$${total.toFixed(2)}`;
  totalPrice.dataset.value = total;
  handleTotalOrderLinesPrice();
}

export function handleTotalOrderLinesPrice() {
  const totalOrderPriceInput = formContainer.querySelector("#total_price");

  const lines = formContainer.querySelectorAll(".order-lines-table-line");
  let totalPrice = 0.0;

  lines.forEach((line) => {
    let totalPriceCell =
      line.querySelector(".total-line-price-cell").dataset.value || 0;
    totalPrice = parseFloat(totalPrice) + parseFloat(totalPriceCell);
  });

  totalOrderPriceInput.value = `${totalPrice.toFixed(2)}`;
}
/*

buildOrderItemsTable is a function that give back the component that allow a user to create order lines for an order

    Product   Quantity    Price
            Add new product

    Product   Quantity    Price
       +          1         0     X
            Add new product
            
    Product   Quantity    Price
     Atomic 
     Habits       1       $12.50     X
            Add new product

*/
