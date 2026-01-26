import {
  fetch_customers_DB,
  get_customer_addresses_DB,
} from "./customerServices.js";

import { customerTableConfigs } from "./customerTableConfigs.js";
import { customerFormConfigs } from "./customerFormConfigs.js";
import { buildCustomerForm } from "./customerFormBuilder.js";
import { get_customer_data_DB } from "./customerServices.js";
import { hydrateCustomerForm } from "./customerFormHydrator.js";
import {
  swapClass,
  renderActiveTableState,
  renderEmptyTableState,
  handlePaginationButtonsColor,
} from "../UIhelpers.js";
import { showMessageLog } from "../messageLog/messageLog.js";
import { listState } from "../adminUIController.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

export async function showCustomerViewForm(customerID) {
  const customerData = await get_customer_data_DB(customerID);
  const customerAddresses = await get_customer_addresses_DB(customerID);
  openForm(customerData.data, customerAddresses);
}

async function openForm(data = {}, customer_addresses = {}) {
  // get form body
  const formBody = document.querySelector(".form-body");
  console.log(customer_addresses);
  // empty it first
  formBody.innerHTML = "";

  // set form title
  const formTitle = document.querySelector(".form-operation-text");
  formTitle.textContent = "VIEW CUSTOMER";

  const formConfigs = customerFormConfigs;

  const form = buildCustomerForm(formConfigs);
  formBody.append(form);

  if (data) {
    hydrateCustomerForm(form, data, customer_addresses);
  }

  swapClass(formContainer, "slide-in-form", "slide-out-form");
}

export function resetCustomerForm(form) {
  const addressesList = form.querySelector(".addresses-list");

  const ordersCountText = form.querySelector(".orders-count-form");
  const totalSpentText = form.querySelector(".total-spent-form");

  ordersCountText.textContent = `Orders Count: 0`;
  totalSpentText.textContent = `Total Spent: $0`;
  addressesList.innerHTML = "";

  const emptyAddressesText = document.createElement("p");
  emptyAddressesText.classList.add("empty-addresses-text");
  emptyAddressesText.textContent = "No Current Addresses";
  addressesList.append(emptyAddressesText);

  form.reset();
}

export async function loadCustomers() {
  try {
    const customersResponse = await fetch_customers_DB({
      page: listState.page,
      perPage: listState.perPage,
    });

    const customers = customersResponse.data;
    const paginationData = customersResponse.pagination;

    listState.page = paginationData.page;
    listState.totalPages = paginationData.totalPages;

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
        pagination: paginationData,
        renderHeader: renderCustomerTableHeader,
        renderRow: renderCustomerTableRow,

        canAdd: false,
      });

      handlePaginationButtonsColor(listState.page);
    }
  } catch (err) {
    showMessageLog("error", err);
    return;
  }
}

function renderCustomerTableHeader() {
  const configs = customerTableConfigs;

  return `
  <div class="flex-table-header">
    ${configs.columns
      .map(
        (columnHeader) =>
          `
          <div>
            <p>${columnHeader.headerTitle}</p>
          </div>
        `,
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
        <div class="order-container">
            <div class="order-counts-container">
                <p> ${item.total_orders} </p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20">
                    <path fill="#000" fill-rule="evenodd" d="M5 4a1 1 0 0 1 0 2H4v1a1 1 0 0 1-2 0V6H1a1 1 0 0 1 0-2h1V3a1 1 0 0 1 2 0v1h1Zm10 14c-.55 0-1-.449-1-1 0-.661.453-.855 1-1.049.547.194 1 .388 1 1.049 0 .551-.449 1-1 1ZM3 18c-.55 0-1-.449-1-1 0-.661.453-.855 1-1.049.547.194 1 .388 1 1.049 0 .551-.449 1-1 1ZM16 3a1 1 0 0 1 1-1h2a1 1 0 0 0 0-2h-3a2 2 0 0 0-2 2v10H4a2 2 0 0 0-2 2v.184a2.991 2.991 0 0 0 .436 5.764A3.002 3.002 0 0 0 6 17a2.99 2.99 0 0 0-2-2.816V14h10v.184a2.991 2.991 0 0 0 .436 5.764A3.002 3.002 0 0 0 18 17a2.99 2.99 0 0 0-2-2.816V3Z"/>
                </svg>
            </div>
            <div class="total-spent-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                    <path fill="none" d="M12 4c-4.411 0-8 3.589-8 8s3.589 8 8 8 8-3.589 8-8-3.589-8-8-8zm1 12.915V18h-2v-1.08c-2.339-.367-3-2.002-3-2.92h2c.011.143.159 1 2 1 1.38 0 2-.585 2-1 0-.324 0-1-2-1-3.48 0-4-1.88-4-3 0-1.288 1.029-2.584 3-2.915V6.012h2v1.109c1.734.41 2.4 1.853 2.4 2.879h-1l-1 .018C13.386 9.638 13.185 9 12 9c-1.299 0-2 .516-2 1 0 .374 0 1 2 1 3.48 0 4 1.88 4 3 0 1.288-1.029 2.584-3 2.915z"/>
                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
                    <path d="M12 11c-2 0-2-.626-2-1 0-.484.701-1 2-1 1.185 0 1.386.638 1.4 1.018l1-.018h1c0-1.026-.666-2.469-2.4-2.879V6.012h-2v1.073C9.029 7.416 8 8.712 8 10c0 1.12.52 3 4 3 2 0 2 .676 2 1 0 .415-.62 1-2 1-1.841 0-1.989-.857-2-1H8c0 .918.661 2.553 3 2.92V18h2v-1.085c1.971-.331 3-1.627 3-2.915 0-1.12-.52-3-4-3z"/>
                </svg>
                <p> $${item.total_spent} </p>
            </div>
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="view" data-entity="customer" data-intent="showView" data-id="${item.id}">
                <svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" fill="none" viewBox="0 0 24 24">
                    <g clip-path="url(#a)">
                        <circle cx="12" cy="13" r="2" stroke="#000" stroke-linejoin="round"/>
                        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M12 7.5c-4.305 0-7.524 3.583-8.605 4.965a.86.86 0 0 0 0 1.07C4.476 14.917 7.695 18.5 12 18.5c4.305 0 7.524-3.583 8.605-4.965a.86.86 0 0 0 0-1.07C19.524 11.083 16.305 7.5 12 7.5Z"/>
                    </g>
                </svg>
            </button>
          </div>
        </div>
  `;
}
