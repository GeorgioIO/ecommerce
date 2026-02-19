import {
  accountDetailsConfig,
  passwordConfig,
} from "./accountDetailsFormConfig.js";

export function buildAccountDetailFormSkeleton() {
  // Create the form
  const form = document.createElement("form");
  form.id = "account-details-form";
  form.noValidate = true;

  // For each field in configuration start creating the container
  accountDetailsConfig.fields.forEach((field) => {
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
    inputTag.type = field.type;

    inputContainer.append(inputLabel, inputTag);

    form.append(inputContainer);
  });

  const fieldset = document.createElement("fieldset");
  fieldset.id = "account-password-fieldset";
  const fieldsetLegend = document.createElement("legend");
  fieldsetLegend.innerHTML = "<strong>Change password</strong>";

  const emptyReminder = document.createElement("p");
  emptyReminder.innerHTML = "<i>Leave unchanged fields empty</i>";

  fieldset.append(fieldsetLegend, emptyReminder);

  passwordConfig.fields.forEach((field) => {
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
    inputTag.type = field.type;

    inputContainer.append(inputLabel, inputTag);

    fieldset.append(inputContainer);
  });

  form.append(fieldset);

  // Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  const saveButton = document.createElement("button");
  saveButton.type = "button";
  saveButton.textContent = "Save changes";
  saveButton.id = "save-account-changes-button";

  buttonsContainer.append(saveButton);

  form.append(buttonsContainer);

  return form;
}
