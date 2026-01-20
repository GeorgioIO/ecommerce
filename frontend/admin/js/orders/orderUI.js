import {
  renderActiveTableState,
  renderEmptyTableState,
  toggleButtonClickablility,
} from "../UIhelpers.js";
import {
  fetchOrders_DB,
  fetchOrderAddress_DB,
  fetchOrderLines_DB,
  get_order_data_DB,
} from "./ordersServices.js";
import {
  fetch_customers_DB,
  get_customer_addresses_DB,
} from "../customers/customerServices.js";
import { orderTableConfigs } from "./orderTableConfigs.js";
import { orderDetailsConfig, orderAddressConfig } from "./orderFormConfigs.js";
import { buildOrderForm } from "./orderFormBuilder.js";
import { swapClass } from "../UIhelpers.js";
import { populateOrderFormSelect } from "./orderFormPopulator.js";
import {
  createOrderLine,
  clearOrderLineTable,
  deleteOrderLineRow,
  handleOrderLinePriceChange,
} from "./orderLines.js";
import { hydrateOrderForm } from "./orderFormHydrator.js";
import { removeSearchBox, showProductSearch } from "./orderLineSearch.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

let currentCustomerAddresses = [];

// ========== LISTENERS ========== //

// Listen to change events in operation form
formContainer.addEventListener("change", async (e) => {
  const customerNamesSelect = e.target.closest("#name");
  const existingAddressSelect = e.target.closest("#existing-address-select");
  const inlineQuantityNumber = e.target.closest(".quantity-line-input");

  // Quantity Inline is changed
  if (inlineQuantityNumber) {
    handleOrderLinePriceChange(
      inlineQuantityNumber.closest(".order-lines-table-line"),
    );
  }

  // Customer name is changed
  if (customerNamesSelect) {
    const addressesContainer = document.querySelector(
      ".address-existing-container",
    );

    // Get customer ID
    const customerID = customerNamesSelect.value;
    const addressesSelect = document.querySelector("#existing-address-select");

    // When 'Select Customer' is picked
    if (!customerID) {
      resetAddressSection();
      addressesContainer.style.display = "none";
      currentCustomerAddresses = [];
      return;
    }

    // Get addresses a specific customer id
    currentCustomerAddresses = await get_customer_addresses_DB(customerID);

    if (currentCustomerAddresses.length) {
      addressesContainer.style.display = "flex";
      populateExistingAddressesSelect(
        addressesSelect,
        currentCustomerAddresses,
      );
    } else {
      resetAddressSection();
      addressesContainer.style.display = "none";
    }
  } else if (existingAddressSelect) {
    // Get address id
    const addressID = Number(existingAddressSelect.value);

    // When 'New address' is selected
    if (!addressID) {
      resetAddressSection();
      return;
    }

    // Get the specific address data from currentCustomerAddresses array
    const address = currentCustomerAddresses.find(
      (addr) => addr.address_id === addressID,
    );

    if (!address) return;

    // Hydrate address section
    hydrateAddressSection(address);
  }
});

// ========== EXPORTED FUNCTIONS ========== //

export function collectOrderFormData(form) {
  /*
  This function is responsible of collecting the overall order data :
  - order meta data
  - order address
  - order lines data
  */
  const orderLines = [...form.querySelectorAll(".order-lines-table-line")].map(
    (row) => ({
      bookId: row.dataset.bookid || null,
      quantity: row.querySelector(".quantity-line-input")?.value ?? "",
      unitPrice: row.querySelector(".unit-price-cell")?.dataset.value ?? "",
      totalLinePrice:
        row.querySelector(".total-line-price-cell")?.dataset.value ?? "",
    }),
  );

  const data = {
    orderMetaData: {
      id: form.querySelector("#id").value,
      name: form.querySelector("#name").value,
      status: form.querySelector("#status").value,
      totalOrderPrice: form.querySelector("#total_price").value,
      dateAdded: form.querySelector("#date_added").value,
    },
    orderAddressDetails: {
      existingAddress:
        form.querySelector("#existing-address-select").value || null,
      firstName: form.querySelector("#first_name").value,
      lastName: form.querySelector("#last_name").value,
      email: form.querySelector("#email").value,
      phoneNumber: form.querySelector("#phone_number").value,
      state: form.querySelector("#state").value,
      city: form.querySelector("#city").value,
      addressLine1: form.querySelector("#address_line1").value,
      addressLine2: form.querySelector("#address_line2").value || null,
      additionalNotes: form.querySelector("#additional_notes").value || null,
    },
    orderLines: orderLines,
  };

  const idInput = form.querySelector("#id");
  if (idInput && idInput.value) {
    data.orderMetaData.id = idInput.value;
  }

  return data;
}

export async function showOrderEditForm(orderID) {
  const orderMetaData = await get_order_data_DB(orderID);
  const orderAddressData = await fetchOrderAddress_DB(orderID);
  const orderLines = await fetchOrderLines_DB(orderID);
  console.log(orderLines);

  openForm("edit", orderMetaData, orderAddressData, orderLines);
}

export async function showOrderAddForm() {
  openForm("add");
}

export function resetOrderForm(form) {
  const mode = form.dataset.mode;

  // reset the form
  form.reset();

  const addressExistingContainer = form.querySelector(
    ".address-existing-container",
  );

  addressExistingContainer.style.display = "none";

  // Clear order line table
  clearOrderLineTable();

  // Remove search box
  removeSearchBox();

  setTimeout(() => {
    const orderDate = form.querySelector("#date_added");
    orderDate.value = new Date().toISOString().split("T")[0];
  }, 0);

  // reset customers
  if (mode === "edit") {
    resetCustomerSelect();
  }

  // reset buttons
  const addOrderLineButton = formContainer.querySelector(
    "#add-new-order-line-button",
  );
  const submitOperationButton = formContainer.querySelector(
    "#order-operation-button",
  );
  toggleButtonClickablility(addOrderLineButton, false);
  toggleButtonClickablility(submitOperationButton, false);
}

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

// ========== LOCAL FUNCTIONS ========== //

function resetCustomerSelect() {
  const customerSelectOptions = formContainer.querySelectorAll("#name option");

  customerSelectOptions.forEach((option) => {
    option.remove();
  });
}

// Function responsible to hydrate address setion
function hydrateAddressSection(address_details) {
  formContainer.querySelector("#first_name").value = address_details.first_name;
  formContainer.querySelector("#last_name").value = address_details.last_name;
  formContainer.querySelector("#phone_number").value =
    address_details.phone_number;
  formContainer.querySelector("#email").value = address_details.email;
  formContainer.querySelector("#state").value = address_details.state;
  formContainer.querySelector("#city").value = address_details.city;
  formContainer.querySelector("#address_line1").value =
    address_details.address_line1;
  formContainer.querySelector("#address_line2").value =
    address_details.address_line2;
  formContainer.querySelector("#additional_notes").value =
    address_details.additional_notes;
}

// function responsible to reset address section
function resetAddressSection() {
  formContainer.querySelector("#first_name").value = "";
  formContainer.querySelector("#last_name").value = "";
  formContainer.querySelector("#phone_number").value = "";
  formContainer.querySelector("#email").value = "";
  formContainer.querySelector("#state").value = "";
  formContainer.querySelector("#city").value = "";
  formContainer.querySelector("#address_line1").value = "";
  formContainer.querySelector("#address_line2").value = "";
  formContainer.querySelector("#additional_notes").value = "";
}

// function responsible to populate existing address select
function populateExistingAddressesSelect(selectElement, existingAddresses) {
  // set address counter to 1
  let addressesCounter = 1;

  // reset select element
  selectElement.innerHTML = "";

  // Select new address option (clickable)
  const newAddressOption = document.createElement("option");
  newAddressOption.textContent = `Use New Address`;
  newAddressOption.value = null;
  selectElement.append(newAddressOption);

  existingAddresses.forEach((address) => {
    // Create group for each address
    const addressGroup = document.createElement("optgroup");
    addressGroup.dataset.addressid = address.address_id;
    addressGroup.label = `Address ${addressesCounter}`;

    // Select option (clickable)
    const selectOption = document.createElement("option");
    selectOption.value = address.address_id;
    selectOption.textContent = `Use Address ${addressesCounter}`;
    addressGroup.append(selectOption);

    // Select option for address details (read only)
    Object.keys(address).forEach((detail) => {
      if (detail !== "address_id" && detail !== "is_default") {
        const address_detail = document.createElement("option");
        address_detail.disabled = true;
        address_detail.value = address[detail];
        address_detail.textContent = `${detail}: ${address[detail]}`;
        addressGroup.append(address_detail);
      }
    });

    addressesCounter++;
    selectElement.append(addressGroup);
  });
}

async function openForm(
  mode,
  orderMetaData = {},
  orderAddressData = {},
  orderLines = {},
) {
  // get form body
  const formBody = document.querySelector(".form-body");

  // empty it first
  formBody.innerHTML = "";

  // set form title
  const formTitle = document.querySelector(".form-operation-text");
  formTitle.textContent = mode === "add" ? "ADD ORDER" : "UPDATE ORDER";

  const orderDetailsConfigs = orderDetailsConfig;
  const orderAddressConfigs = orderAddressConfig;

  const form = buildOrderForm(mode, orderDetailsConfigs, orderAddressConfigs);
  formBody.append(form);

  await populateOrderFormSelect(form, mode, orderMetaData);

  if (mode === "edit") {
    hydrateOrderForm(form, orderMetaData, orderAddressData, orderLines);

    if (
      orderMetaData.status !== "Pending" &&
      orderMetaData.status !== "Processing"
    ) {
      const addOrderLineButton = formContainer.querySelector(
        "#add-new-order-line-button",
      );
      const submitOperationButton = formContainer.querySelector(
        "#order-operation-button",
      );
      toggleButtonClickablility(addOrderLineButton, true);
      toggleButtonClickablility(submitOperationButton, true);
    }
  }

  swapClass(formContainer, "slide-in-form", "slide-out-form");
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
        `,
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
