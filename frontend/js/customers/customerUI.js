import { renderActiveTableState } from "../UIhelpers.js";
import { fetch_customers_DB } from "./customerServices.js";
import { renderActiveTableState, renderEmptyTableState } from "../UIhelpers.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

export async function loadCustomers() {
  try {
    const customers = await fetch_customers_DB();

    if (customers.length === 0) {
      content.innerHTML = renderEmptyTableState({
        entity: "customer",
        label: "Customer",
        canAdd: false,
      });
    } else {
      content.innerHTML = renderActiveTableState({
        entity: "customer",
        label: "Customer",
        data: customers,
        renderHeader: renderCustomerTableHeader,
        renderRow: renderCustomerTableRow,
        canAdd: false,
      });
    }
  } catch (err) {
    console.log(err);
  }
}

function renderCustomerTableHeader() {
  const configs = authorTableConfigs;

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

function renderCustomerTableRow(item) {
  return `
    <div class="flex-table-row">
        <div>
            <p> ${item.customer_code} </p>
        </div>
        <div>
            <p> ${item.name} </p>
        </div>
        <div>
            <p> ${item.email} </p>
        </div>
        <div>
            <p> ${item.phone_number} </p>
        </div>
        <div>
            <p> test </p>
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="edit" data-entity="author" data-intent="showEdit" data-id="${item.id}">
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
