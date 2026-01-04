import { fetch_authors_DB, get_author_data_DB } from "./authorServices.js";
import { authorFormConfigs } from "./authorFormConfigs.js";
import { authorTableConfigs } from "./authorTableConfigs.js";
import { buildAuthorForm } from "./authorFormBuilder.js";
import { hydrateAuthorForm } from "./authorFormHydrator.js";
import { swapClass } from "../helpers.js";
import { renderActiveTableState, renderEmptyTableState } from "../UIhelpers.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

export async function showAuthorAddForm() {
  openForm("add");
}

export async function showAuthorEditForm(authorID) {
  const authorData = await get_author_data_DB(authorID);
  openForm("edit", authorData.data);
}

export function collectAuthorFormData(form) {
  const data = {
    name: form.querySelector("#name").value,
  };

  const idInput = form.querySelector("#id");
  if (idInput && idInput.value) {
    data.id = idInput.value;
  }

  return data;
}

// Function to load author from the database by sending a request to backend
export async function loadAuthors() {
  try {
    const authors = await fetch_authors_DB();

    if (authors.length === 0) {
      content.innerHTML = renderEmptyTableState({
        entity: "author",
        label: "Author",
        canAdd: true,
      });
    } else {
      content.innerHTML = renderActiveTableState({
        entity: "author",
        label: "Author",
        data: authors,
        renderHeader: renderAuthorTableHeader,
        renderRow: renderAuthorTableRow,
        canAdd: true,
      });
    }
  } catch (err) {
    console.log(err);
  }
}

export async function populateSelectAuthors(selectElement) {
  const authors = await fetch_authors_DB();

  // Default option
  const defaultOptionElement = document.createElement("option");
  defaultOptionElement.value = "";
  defaultOptionElement.textContent = "Select Author";
  selectElement.append(defaultOptionElement);

  authors.forEach((author) => {
    let optionElement = document.createElement("option");
    optionElement.value = author.id;
    optionElement.textContent = author.name;
    selectElement.append(optionElement);
  });
}

export async function handleAuthorAdd() {
  openForm("add");
}

async function openForm(mode, data = {}) {
  // get form body
  const formBody = document.querySelector(".form-body");

  // empty it first
  formBody.innerHTML = "";

  // set form title
  const formTitle = document.querySelector(".form-operation-text");
  formTitle.textContent = mode === "add" ? "ADD AUTHOR" : "UPDATE AUTHOR";

  const formConfigs = authorFormConfigs;

  const form = buildAuthorForm(mode, formConfigs);
  formBody.append(form);

  if (mode === "edit") {
    hydrateAuthorForm(form, data);
  }

  swapClass(formContainer, "slide-in-form", "slide-out-form");
}

export function resetAuthorForm(form) {
  form.reset();
}

function renderAuthorTableHeader() {
  const configs = authorTableConfigs;

  return `
  <div class="flex-table-header">
    ${configs.columns
      .map(
        (columnHeader) =>
          `
          <div>
            <p>${columnHeader.headerTitle}</p>
          </div>
        `
      )
      .join("")}
  </div>
  `;
}

function renderAuthorTableRow(item) {
  return `
    <div class="flex-table-row">
        <div>
            <p> ${item.name} </p>
        </div>
        <div>
            <p> test </p>
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="edit" data-entity="author" data-intent="showEdit" data-id="${item.id}">
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
            <button class="table-row-button show-confirmation-modal" data-mode="delete" data-intent="showDelete" data-intent="deleteAuthor" data-entity="author" data-id="${item.id}">
                <svg
                    width="25px"
                    height="25px" 
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M5.73708 6.54391V18.9857C5.73708 19.7449 6.35257 20.3604 7.11182 20.3604H16.8893C17.6485 20.3604 18.264 19.7449 18.264 18.9857V6.54391M2.90906 6.54391H21.0909"
                        stroke="#1C1C1C"
                        stroke-width="1.3"
                        stroke-linecap="round"
                    />
                    <path
                        d="M8 6V4.41421C8 3.63317 8.63317 3 9.41421 3H14.5858C15.3668 3 16 3.63317 16 4.41421V6"
                        stroke="#1C1C1C"
                        stroke-width="1.3"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
          </div>
        </div>
  `;
}
