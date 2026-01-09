import { populateSelectAuthors } from "../authors/authorsUI.js";
import { populateSelectGenres } from "../genres/genresUI.js";
import { populateSelectLanguages } from "../languages/languages.js";
import { populateSelectFormats } from "../formats/formats.js";

const selectSources = {
  authors: populateSelectAuthors,
  genres: populateSelectGenres,
  languages: populateSelectLanguages,
  formats: populateSelectFormats,
};

export function buildBookForm(mode = "add", config) {
  // Create the form
  const form = document.createElement("form");
  form.dataset.entity = "book";
  form.dataset.mode = mode;
  form.noValidate = true;

  // For each field in configuration start creating the container
  config.fields.forEach((field) => {
    // container
    const inputContainer = document.createElement("div");
    inputContainer.classList.add(
      field.tag === "input"
        ? "input-container"
        : field.tag === "textarea"
        ? "textarea-container"
        : "input-container"
    );

    // label
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

    // in case the field is an input tag and need type
    if (field.tag === "input") {
      inputTag.type = field.type;
      // Add step attribute for number inputs if specified
      if (field.type === "number" && field.step !== undefined) {
        inputTag.step = field.step;
      }
    }

    inputContainer.append(inputTag);

    form.append(inputContainer);

    // Create image element after file
    if (field.type === "file") {
      const imgElementContainer = document.createElement("div");
      imgElementContainer.classList.add("image-element-container");

      const imgElement = document.createElement("img");
      imgElement.classList.add("entity-image-display");

      const emptyImgText = document.createElement("p");
      emptyImgText.classList.add("empty-image-text");
      emptyImgText.textContent = "No image set.";
      imgElementContainer.append(imgElement, emptyImgText);

      form.append(imgElementContainer);
    }
  });

  // Create buttons container
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  // create operation button (submit)
  const submitButton = document.createElement("button");
  submitButton.id = "book-operation-button";
  submitButton.type = "submit";
  submitButton.textContent = "SUBMIT";
  submitButton.dataset.intent = mode === "add" ? "addEntity" : "updateEntity";

  // create reset button
  const resetButton = document.createElement("button");
  resetButton.type = "reset";
  resetButton.textContent = "RESET";
  resetButton.dataset.intent = "resetForm";

  buttonsContainer.append(submitButton, resetButton);

  form.append(buttonsContainer);

  return form;
}
