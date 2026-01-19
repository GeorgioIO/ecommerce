import { buildOrderLinesSection } from "./orderLines.js";

export function buildOrderForm(
  mode = "add",
  orderDetailConfigs,
  orderAddressConfigs
) {
  // Create the form
  const form = document.createElement("form");
  form.dataset.entity = "order";
  form.dataset.mode = mode;
  form.noValidate = true;

  // Append all sections
  form.append(buildOrderMetaDetailsSection(orderDetailConfigs));
  form.append(buildOrderAddressSection(orderAddressConfigs));
  form.append(buildOrderLinesSection());
  form.append(buildOrderFormButtons(mode));

  return form;
}

function buildOrderFormButtons(mode) {
  //  Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  //  create operation button (submit)
  const submitButton = document.createElement("button");
  submitButton.id = "order-operation-button";
  submitButton.type = "submit";
  submitButton.textContent = "SUBMIT";
  submitButton.dataset.intent = mode === "add" ? "addEntity" : "updateEntity";

  //  create reset button
  const resetButton = document.createElement("button");
  resetButton.type = "reset";
  resetButton.textContent = "RESET";
  resetButton.dataset.intent = "resetForm";
  buttonsContainer.append(submitButton, resetButton);

  return buttonsContainer;
}

function buildOrderAddressSection(configs) {
  // Create section
  const orderAddressSection = document.createElement("section");
  orderAddressSection.classList.add("order-form-section");

  // Create title
  const orderAddressSectionTitle = document.createElement("h3");
  orderAddressSectionTitle.textContent = "Order Address : ";
  orderAddressSectionTitle.classList.add("order-form-section-title");

  // Append title
  orderAddressSection.append(orderAddressSectionTitle);

  // Existing address container (initially hidden)
  const addressContainer = document.createElement("div");
  addressContainer.classList.add("address-existing-container");
  addressContainer.style.display = "none";

  // Label text
  const text = document.createElement("p");
  text.textContent = "Select an existing address";

  // Select element
  const addressesSelect = document.createElement("select");
  addressesSelect.id = "existing-address-select";
  addressesSelect.name = "existing_address";

  // Default option
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "Select address";
  defaultOption.disabled = true;
  defaultOption.selected = true;

  addressesSelect.append(defaultOption);

  // append to container
  addressContainer.append(text, addressesSelect);

  orderAddressSection.append(addressContainer);

  configs.fields.forEach((field) => {
    // Container
    const inputContainer = document.createElement("div");
    inputContainer.classList.add(
      field.tag === "input"
        ? "input-container"
        : field.tag === "textarea"
        ? "textarea-container"
        : "input-container"
    );

    //  label
    const inputLabel = document.createElement("label");
    inputLabel.innerText = field.labelText;
    inputLabel.htmlFor = field.name.toLowerCase();

    //  Add Label
    inputContainer.append(inputLabel);

    //  HTML Tag
    const inputTag = document.createElement(field.tag);
    inputTag.name = field.name;
    inputTag.id = field.key;
    inputTag.required = field.required;
    inputTag.disabled = field.disabled;
    inputTag.placeholder = field.placeholderText || "";

    //  in case the field is an input tag and need type
    if (field.tag === "input") {
      inputTag.type = field.type;
    }

    inputContainer.append(inputTag);

    orderAddressSection.append(inputContainer);
  });

  return orderAddressSection;
}

function buildOrderMetaDetailsSection(configs) {
  // Create section
  const orderDetailsSection = document.createElement("section");
  orderDetailsSection.classList.add("order-form-section");

  // Create title
  const orderAddressSectionTitle = document.createElement("h3");
  orderAddressSectionTitle.textContent = "Order Details : ";
  orderAddressSectionTitle.classList.add("order-form-section-title");

  orderDetailsSection.append(orderAddressSectionTitle);

  configs.fields.forEach((field) => {
    //  container
    const inputContainer = document.createElement("div");
    inputContainer.classList.add("input-container");

    //  label
    const inputLabel = document.createElement("label");
    inputLabel.innerText = field.labelText;
    inputLabel.htmlFor = field.name.toLowerCase();

    //  Add Label
    inputContainer.append(inputLabel);

    //  HTML Tag
    const inputTag = document.createElement(field.tag);
    inputTag.name = field.name;
    inputTag.id = field.key;
    inputTag.required = field.required;
    inputTag.disabled = field.disabled;
    inputTag.readOnly = field.readonly;
    inputTag.placeholder = field.placeholderText || "";

    //  in case the field is an input tag and need type
    if (field.tag === "input") {
      inputTag.type = field.type;

      // Add step attribute for number inputs if specified
      if (field.type === "number" && field.step !== undefined) {
        inputTag.step = field.step;
      }

      // if date add today date
      if (field.type === "date") {
        inputTag.value = new Date().toISOString().split("T")[0];
      }
    }

    inputContainer.append(inputTag);

    orderDetailsSection.append(inputContainer);
  });

  return orderDetailsSection;
}
