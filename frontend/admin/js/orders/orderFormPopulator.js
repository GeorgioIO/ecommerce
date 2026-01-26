import { fetch_customers_DB } from "../customers/customerServices.js";
import { showMessageLog } from "../messageLog/messageLog.js";

// ====== EXPORTED FUNCTIONS

export async function populateOrderFormSelect(
  form,
  mode = "add",
  orderMetaData = {},
) {
  const statusSelect = form.querySelector("#status");
  const customersSelect = form.querySelector("#name");

  await populateCustomerSelect(customersSelect, mode, orderMetaData);
  populateSelectOrderStatus(statusSelect);
}

async function populateCustomerSelect(selectElement, mode, data) {
  /*
    Populate customer select can have two modes :
    - add :
        - Get all customers and add them to the select in this format : customername - customeremail - customerrole
    - edit :
        - Customer data will be passed to it , and add him in the same format
  
  */
  if (mode === "add") {
    try {
      // Get customers
      const customers = await fetch_customers_DB();

      // Default option
      const defaultOptionElement = document.createElement("option");
      defaultOptionElement.value = "";
      defaultOptionElement.textContent = "Select Customer";
      selectElement.append(defaultOptionElement);

      customers.data.forEach((customer) => {
        let optionElement = document.createElement("option");
        optionElement.value = customer.id;
        optionElement.textContent = `${customer.name} - ${customer.email} - ${customer.role}`;
        selectElement.append(optionElement);
      });
    } catch (error) {
      showMessageLog(error);
      return;
    }
  } else if (mode === "edit") {
    let optionElement = document.createElement("option");
    optionElement.value = data.user_id;
    optionElement.textContent = `${data.customer_name} - ${data.email} - ${data.role}`;
    selectElement.append(optionElement);
  }
}

// ====== LOCAL FUNCTIONS =====
function populateSelectOrderStatus(selectElement) {
  const optDefault = new Option("Select Status", "");
  const opt1 = new Option("Pending", "Pending");
  const opt2 = new Option("Processing", "Processing");
  const opt3 = new Option("Shipped", "Shipped");
  const opt4 = new Option("Delivered", "Delivered");
  const opt5 = new Option("Cancelled", "Cancelled");
  const opt6 = new Option("Refunded", "Refunded");

  selectElement.append(optDefault, opt1, opt2, opt3, opt4, opt5, opt6);
}
