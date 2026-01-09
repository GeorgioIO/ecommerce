/*
Function responsible to build the form based on its configuration
Input : 
    - mode : view (customers only available mode)
    - formConfiguration : object of the form configuration

*/
export function buildCustomerForm(config) {
  // Create the form
  const form = document.createElement("form");
  form.dataset.entity = "customer";
  form.dataset.mode = "view";
  form.noValidate = true;

  const orderDetailsContainer = document.createElement("div");
  orderDetailsContainer.classList.add("order-form-details");

  const ordersCount = document.createElement("p");
  ordersCount.classList.add("orders-count-form");

  const totalSpent = document.createElement("p");
  totalSpent.classList.add("total-spent-form");

  orderDetailsContainer.append(ordersCount, totalSpent);

  form.append(orderDetailsContainer);

  // For each field in configuration start creating the container
  config.fields.forEach((field) => {
    const inputContainer = document.createElement("div");
    inputContainer.classList.add("input-container");

    // Label
    const inputLabel = document.createElement("label");
    inputLabel.innerText = field.label;
    inputLabel.htmlFor = field.name.toLowerCase();

    // Add Label
    inputContainer.append(inputLabel);

    // HTML Tag
    const inputTag = document.createElement(field.tag);
    inputTag.name = field.name;
    inputTag.id = field.key;
    inputTag.required = field.required;
    inputTag.disabled = field.disabled;
    inputTag.type = field.type;

    inputContainer.append(inputTag);

    form.append(inputContainer);

    if (inputTag.type === "password") {
      const addressesContainer = document.createElement("div");
      addressesContainer.classList.add("addresses-container");

      const addressesTitle = document.createElement("p");
      addressesTitle.classList.add("addresses-container-title");
      addressesTitle.textContent = "Addresses";

      const addressesList = document.createElement("ul");
      addressesList.classList.add("addresses-list");

      addressesContainer.append(addressesTitle, addressesList);
      form.append(addressesContainer);
    }
  });

  // Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  // Create reset button
  const resetButton = document.createElement("button");
  resetButton.type = "reset";
  resetButton.textContent = "RESET";
  resetButton.dataset.intent = "resetForm";

  buttonsContainer.append(resetButton);

  form.append(buttonsContainer);

  return form;
}
