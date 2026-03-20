import { AddressConfig } from "./addressFormConfigs.js";

export function buildAddressFormSkeleton(data, destination = null) {
  // Create the form
  const form = document.createElement("form");
  form.id = "address-form";
  form.noValidate = true;

  if (destination !== "checkout") {
    const defaultReminderText = document.createElement("p");
    defaultReminderText.innerHTML =
      "<strong>This address will be used as the default address in orders</strong>";

    form.append(defaultReminderText);
  }

  if (!data || data.length === 0) {
    const noAddressText = document.createElement("p");
    noAddressText.innerHTML = "<i>Currently you don't have any address set</i>";
    form.append(noAddressText);
  }

  const doubleRow = document.createElement("div");
  doubleRow.classList.add("form-double-row");

  ["first_name", "last_name"].forEach((fieldName) => {
    const field = AddressConfig.fields.find((f) => f.name === fieldName);

    const wrapper = document.createElement("div");
    wrapper.classList.add("double-field");

    const inputLabel = document.createElement("label");
    inputLabel.innerHTML = field.required
      ? `${field.labelText}<span class="required-asteriks">*</span>`
      : field.labelText;
    inputLabel.htmlFor = field.name.toLowerCase();

    const inputTag = document.createElement(field.tag);
    inputTag.name = field.name;
    inputTag.id = field.key;
    inputTag.required = field.required;
    inputTag.disabled = field.disabled;
    inputTag.type = field.type;

    wrapper.append(inputLabel, inputTag);
    doubleRow.append(wrapper);
  });

  form.append(doubleRow);

  // For each field in configuration start creating the container
  AddressConfig.fields.forEach((field) => {
    if (field.name === "first_name" || field.name === "last_name") return;
    const inputContainer = document.createElement("div");
    inputContainer.classList.add("form-row");

    // Label
    const inputLabel = document.createElement("label");
    inputLabel.innerHTML = field.required
      ? `${field.labelText}<span class="required-asteriks">*</span>`
      : field.labelText;
    inputLabel.htmlFor = field.name.toLowerCase();

    // HTML Tag
    const inputTag = document.createElement(field.tag);
    inputTag.name = field.name;
    inputTag.id = field.key;
    inputTag.required = field.required;
    inputTag.disabled = field.disabled;
    if (field.tag === "input") {
      inputTag.type = field.type;
    }

    inputContainer.append(inputLabel, inputTag);

    form.append(inputContainer);
  });

  if (destination !== "checkout") {
    // Create buttons container
    const buttonsContainer = document.createElement("div");
    buttonsContainer.classList.add("buttons-container");

    // Create reset button
    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.textContent = "Delete";
    deleteButton.id = "delete-address-button";
    if (data) deleteButton.dataset.addressid = data.address_id ?? 0;

    const saveButton = document.createElement("button");
    saveButton.type = "button";
    saveButton.textContent = "Save address";
    saveButton.id = "save-address-button";

    buttonsContainer.append(saveButton, deleteButton);

    form.append(buttonsContainer);
  }

  return form;
}
