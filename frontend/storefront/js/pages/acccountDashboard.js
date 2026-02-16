import { fetchOrders_DB } from "../services/ordersServices.js";
import { getSession } from "../services/sessionServices.js";
import { renderOrdersCatalog } from "../components/ordersCatalog.js";
import {
  deleteCustomerAddress_DB,
  get_customer_addresses_DB,
  saveCustomerAddress_DB,
} from "../services/customerServices.js";
import { buildAddressFormSkeleton } from "../components/addressForm/addressFormBuilder.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "../components/messageBox.js";
import { hydrateAddressForm } from "../components/addressForm/addressFormHydrator.js";
import { validateAddressID } from "../core/validators/addressValidators.js";
import { collectFormData } from "../components/addressForm/addressFormCollector.js";
import { validateAddressData } from "../components/addressForm/addressFormValidator.js";

const dashboard = document.querySelector("#user-dashboard") ?? null;
const dashboardSidebar = dashboard?.querySelector("#user-dashboard-sidebar");

export const ordersListState = {
  page: 1,
  perPage: 5,
};

document.addEventListener("DOMContentLoaded", async () => {
  const path = window.location.pathname;
  const sessionData = await getSession();

  if (path.includes("my-account.php") && sessionData.user_id) {
    renderDashboardSection(sessionData);
  }
});

dashboard.addEventListener("click", async (e) => {
  const deleteAddressButton = e.target.closest("#delete-address-button");
  const saveAddressButton = e.target.closest("#save-address-button");

  if (deleteAddressButton) {
    // Get address id
    const address_id = deleteAddressButton.dataset.addressid;
    const form = document.querySelector("#address-form");

    // Delete address
    const response = await deleteCustomerAddress_DB(address_id);
    activateMessageBox();

    if (response.success === false) {
      const messageBox = createMesssageBox(response.message);
      appendMessageBox(messageBox);
      return;
    }

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);
    form.reset();
  }

  if (saveAddressButton) {
    activateMessageBox();
    const form = document.querySelector("#address-form");

    // Collect data
    const data = collectFormData(form);

    // Validate data
    const dataValidation = validateAddressData(data);
    if (!dataValidation.valid) {
      const messageBox = createMesssageBox(dataValidation.error);
      appendMessageBox(messageBox);
      return;
    }

    // Send request
    const response = await saveCustomerAddress_DB(data);

    // Act based on request
    if (!response.success) {
      const messageBox = createMesssageBox(response.message);
      appendMessageBox(messageBox);
      return;
    }

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);
  }
});

dashboardSidebar?.addEventListener("click", async (e) => {
  const sidebarList = e.target.closest(".sidebar-list");

  if (sidebarList) {
    if (sidebarList.classList.contains("active-subsection")) return;
    const content = document.querySelector("#user-dashboard-content");
    const section = sidebarList.dataset.section;
    changeSidebarSection(dashboardSidebar, sidebarList);
    content.innerHTML = "";

    if (section === "dashboard") {
      const sessionData = await getSession();
      renderDashboardSection(sessionData);
    } else if (section === "orders") {
      // TODO : Load orders function
      await renderOrdersCatalog(content, ordersListState);
    } else if (section === "addresses") {
      await renderAddressSection(content);
    } else if (section === "log-out") {
      window.location.href = "/ecommerce/backend/auth/user_logout.php";
    } else {
      document.querySelector("#user-dashboard-content").innerHTML = "";
    }
  }
});

async function renderAddressSection(content) {
  // Display first paragraph tell him that the address will be used as default
  // Build the form if user have a user made address saved populate the form
  // If not tell him that He can fill the form and save address
  // Add delete address button that delete the address and make the form empty
  const address = await get_customer_addresses_DB();
  const sessionData = await getSession();

  if (address.success === false) {
    activateMessageBox();
    const message = createMesssageBox("Fail loading address");
    appendMessageBox(message);
  }

  const form = buildAddressFormSkeleton(address);

  if (address) {
    hydrateAddressForm(form, address[0]);
  }

  content.append(form);
}

export async function renderOrdersSection(state) {
  const response = await fetchOrders_DB({
    page: state.page,
    perPage: state.perPage,
  });

  console.log(response);
}

function renderDashboardSection(data) {
  const contentSection = document.querySelector("#user-dashboard-content");
  contentSection.innerHTML = "";

  const firstParagraph = document.createElement("p");
  firstParagraph.classList.add("welcome-text");
  firstParagraph.innerHTML = `Hello <strong>${data.user_name}</span>`;

  const secondParagraph = document.createElement("p");
  secondParagraph.textContent =
    "From your account dashboard you can view your recent orders, maange your shipping addresses, and edit your password and account details";
  secondParagraph.classList.add("dashboard-introduction");

  contentSection.append(firstParagraph, secondParagraph);
}

function changeSidebarSection(sidebar, target) {
  const sidebarLists = sidebar.querySelectorAll(".sidebar-list");

  sidebarLists.forEach((list) => list.classList.remove("active-subsection"));

  target.classList.add("active-subsection");
}
