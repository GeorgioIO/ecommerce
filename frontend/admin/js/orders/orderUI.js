import { renderActiveTableState, renderEmptyTableState } from "../UIhelpers.js";
import { fetchOrders_DB } from "./ordersServices.js";
import { orderTableConfigs } from "./orderTableConfigs.js";
export function populateSelectOrderStatus() {
  const optDefault = new Option("Select Status", "");
  const opt1 = new Option("Processing", "Processing");
  const opt2 = new Option("Shipped", "Shipped");
  const opt3 = new Option("Delivered", "Delivered");
  const opt4 = new Option("Cancelled", "Cancelled");
  const opt5 = new Option("Refunded", "Refunded");

  selectEl.append(optDefault, opt1, opt2, opt3, opt4, opt5);
}

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

// Function to load books from the database by sending a request to backend
export async function loadOrders() {
  try {
    const books = await fetchOrders_DB();

    if (books.length === 0) {
      content.innerHTML = renderEmptyTableState({
        entity: "order",
        label: "Order",
        canAdd: true,
      });
    } else {
      content.innerHTML = renderActiveTableState({
        entity: "order",
        label: "Order",
        data: books,
        renderHeader: renderOrderTableHeader,
        renderRow: renderOrderTableRow,
        canAdd: true,
      });
    }
  } catch (err) {
    console.log(err);
  }
}

function renderOrderTableHeader() {
  const configs = orderTableConfigs;

  return `
  <div class="flex-table-header">
    ${configs.columns
      .map(
        (columnHeader) =>
          `
          <div>
            <p>${columnHeader.headerTitle}</p>
          </div>
        `
      )
      .join("")}
  </div>
  `;
}

function renderOrderTableRow(item) {
  return `
    <div class="flex-table-row">
        <div>
            <p> ${item.order_code} </p>
        </div>
        <div>
            <p> ${item.name} </p>
        </div>
        <div>
            <p> $${item.total_price} </p>
        </div>
        <div>
            <p> ${item.status} </p>
        </div>   
        <div>
            <p> ${item.display_date} </p>
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="edit" data-entity="order" data-intent="showEdit" data-id="${item.id}">
                <svg
                    width="25px"
                    height="25px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    >
                    <path
                        d="M15.6287 5.12132L4.31497 16.435M15.6287 5.12132L19.1642 8.65685M15.6287 5.12132L17.0429 3.70711C17.4334 3.31658 18.0666 3.31658 18.4571 3.70711L20.5784 5.82843C20.969 6.21895 20.969 6.85212 20.5784 7.24264L19.1642 8.65685M7.85051 19.9706L4.31497 16.435M7.85051 19.9706L19.1642 8.65685M7.85051 19.9706L3.25431 21.0312L4.31497 16.435"
                        stroke="#000000"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </button>
          </div>
        </div>
  `;
}
