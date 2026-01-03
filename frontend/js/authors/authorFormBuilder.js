import { authorFormConfigs } from "./authorFormConfigs.js";

/*
Function responsible to build the form based on its configuration
Input : 
    - mode : either add or edit
    - formConfiguration : object of the form configuration

*/
export function buildAuthorForm(mode = "add", config) {
  // Create the form
  const form = document.createElement("form");
  form.dataset.entity = "author";
  form.dataset.mode = mode;
  form.noValidate = true;

  // For each field in configuration start creating the container
  config.fields.forEach((field) => {
    const inputContainer = document.createElement("div");
    inputContainer.classList.add("input-container");

    // Label
    const inputLabel = document.createElement("label");
    inputLabel.innerText = field.name;
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
  });

  // Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  // Create operation button (submit)
  const submitButton = document.createElement("button");
  submitButton.id = "author-operation-button";
  submitButton.type = "submit";
  submitButton.textContent = "SUBMIT";
  submitButton.dataset.intent = "addEntity";

  // Create reset button
  const resetButton = document.createElement("button");
  resetButton.type = "reset";
  resetButton.textContent = "RESET";
  resetButton.dataset.intent = "resetForm";

  buttonsContainer.append(submitButton, resetButton);

  form.append(buttonsContainer);

  return form;
}
