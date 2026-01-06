import { fetch_genres_DB, getGenreData_DB } from "./genreServices.js";
import { renderActiveTableState, renderEmptyTableState } from "../UIhelpers.js";
import { genreTableConfigs } from "./genreTableConfigs.js";
import { swapClass, handleEntityImageElement } from "../helpers.js";
import { hydrateGenreForm } from "./genreFormHydrator.js";
import { buildGenreForm } from "./genreFormBuilder.js";
import { genreFormConfigs } from "./genreFormConfigs.js";

const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

export async function showGenreAddForm() {
  openForm("add");
}

export async function showGenreEditForm(genreID) {
  const genreData = await getGenreData_DB(genreID);
  openForm("edit", genreData.data);
}

export function collectGenreFormData(form) {
  const data = {
    name: form.querySelector("#name").value,
    image: form.querySelector("#image").files[0] || null,
  };

  const idInput = form.querySelector("#id");
  if (idInput && idInput.value) {
    data.id = idInput.value;
  }

  return data;
}

export async function loadGenres() {
  try {
    const genres = await fetch_genres_DB();

    if (genres.length === 0) {
      content.innerHTML = renderEmptyTableState({
        entity: "genre",
        label: "Genre",
        canAdd: true,
      });
    } else {
      content.innerHTML = renderActiveTableState({
        entity: "genre",
        label: "Genre",
        data: genres,
        renderHeader: renderGenreTableHeader,
        renderRow: renderGenreTableRow,
        canAdd: true,
      });
    }
  } catch (err) {
    console.log(err);
  }
}

function renderGenreTableHeader() {
  const configs = genreTableConfigs;

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

function renderGenreTableRow(item) {
  return `
    <div class="flex-table-row">
        <div>
            <p> ${item.name} </p>
        </div>
        <div>
              ${
                item.image === null
                  ? `<p> No image </p>`
                  : `<img  class="genre-image" src="../assets/images/${item.image}" alt="${item.name} display image">`
              }
        </div>
        <div>
            <button class="cascade-show-books-button" data-entity="book" data-intent="cascadeBooks" data-filterf="genre_id" data-id="${
              item.id
            }">
              Show Books
              <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
                <path stroke="#464455" stroke-linecap="round" stroke-linejoin="round" d="M5 12V6a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-6m-3.889-7H12m0 0v3.889M12 12l-7 7"/>
              </svg>
            </button>
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="edit" data-entity="genre" data-intent="showEdit" data-id="${
              item.id
            }">
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
            <button class="table-row-button show-confirmation-modal" data-mode="delete" data-entity="genre" data-id="${
              item.id
            }">
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

export async function openForm(mode, data = {}) {
  // get form body
  const formBody = document.querySelector(".form-body");

  // empty it first
  formBody.innerHTML = "";

  // set form title
  const formTitle = document.querySelector(".form-operation-text");
  formTitle.textContent = mode === "add" ? "ADD GENRE" : "UPDATE GENRE";

  const formConfigs = genreFormConfigs;

  const form = buildGenreForm(mode, formConfigs);
  formBody.append(form);

  if (mode === "edit") {
    hydrateGenreForm(form, data);
  }

  swapClass(formContainer, "slide-in-form", "slide-out-form");
}

export function resetGenreForm(form) {
  form.reset();

  handleEntityImageElement("reset");
}

export async function populateSelectGenres(selectElement) {
  const genres = await fetch_genres_DB();

  // Default option
  const defaultOptionElement = document.createElement("option");
  defaultOptionElement.value = "";
  defaultOptionElement.textContent = "Select Genre";
  selectElement.append(defaultOptionElement);

  genres.forEach((genre) => {
    let optionElement = document.createElement("option");
    optionElement.value = genre.id;
    optionElement.textContent = genre.name;
    selectElement.append(optionElement);
  });
}
