import {
  handleEntityImageElement,
  swapClass,
  renderActiveTableState,
  renderEmptyTableState,
  handlePaginationButtonsColor,
} from "../UIhelpers.js";
import { get_book_data_DB, fetch_books_DB } from "../books/booksService.js";
import { buildBookForm } from "../books/bookFormBuilder.js";
import { hydrateBookForm } from "./bookFormHydrator.js";

import { bookTableConfigs } from "./bookTableConfigs.js";
import { populateBookFormSelects } from "./bookFormPopulator.js";
import { bookFormConfigs } from "./bookFormConfigs.js";
import { createPaginationButtons } from "../pagination/paginationUI.js";
import { listState } from "../adminUIController.js";
const content = document.querySelector(".table-container");
const formContainer = document.querySelector(".form-container");

export async function showBookAddForm() {
  openForm("add");
}

export async function showBookEditForm(bookID) {
  const bookData = await get_book_data_DB(bookID);
  openForm("edit", bookData.data);
}

// Function to load books from the database by sending a request to backend
export async function loadBooks() {
  console.log(listState);
  try {
    const booksResponse = await fetch_books_DB(listState.filters, {
      page: listState.page,
      perPage: listState.perPage,
    });

    const books = booksResponse.data;
    const paginationData = booksResponse.pagination;

    listState.page = paginationData.page;
    listState.totalPages = paginationData.totalPages;

    if (books.length === 0) {
      content.innerHTML = renderEmptyTableState({
        entity: "book",
        label: "Book",
        canAdd: true,
      });
    } else {
      content.innerHTML = renderActiveTableState({
        entity: "book",
        label: "Book",
        data: books,
        pagination: paginationData,
        renderHeader: renderBookTableHeader,
        renderRow: renderBookTableRow,
        canAdd: true,
      });

      handlePaginationButtonsColor(listState.page);
    }
  } catch (err) {
    console.log(err);
  }
}

function renderBookTableHeader() {
  const configs = bookTableConfigs;

  return `
  <div class="flex-table-header">
    ${configs.columns
      .map(
        (columnHeader) =>
          `
          <div>
            <p>${columnHeader.headerTitle}</p>
          </div>
        `,
      )
      .join("")}
  </div>
  `;
}

function renderBookTableRow(item) {
  return `
    <div class="flex-table-row">
        <div>
            <p> ${item.title} </p>
        </div>
        <div>
            <p> ${item.language} </p>
        </div>
        <div>
            <p> ${item.format} </p>
        </div>
        <div>
            <p> ${item.author_name} </p>
        </div>   
        <div>
            <p> ${item.genre_title} </p>
        </div>
        <div>
            <p> $${item.price} </p>
        </div>
        <div>
            <p> ${item.stock_quantity} </p>
        </div>
        <div class="${item.is_inStock === 0 ? "out-of-stock" : "active-stock"}">
                ${
                  item.is_inStock === 0
                    ? "<p>Out Of Stock</p>"
                    : "<p>Active</p>"
                }  
        </div>
        <div class="action-btns-container">
            <button class="table-row-button open-operation-form" data-mode="edit" data-entity="book" data-intent="showEdit" data-id="${
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
            <button class="table-row-button show-confirmation-modal" data-mode="delete" data-entity="book" data-id="${
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

/*
collectBookFormData(form) -> Function used to collect data from a form
Input : form (the form itself)
Output : the data in the form
Side effects : none

*/
export function collectBookFormData(form) {
  const data = {
    isbn: form.querySelector("#isbn").value,
    sku: form.querySelector("#sku").value,
    title: form.querySelector("#title").value,
    language: form.querySelector("#language").value,
    author: form.querySelector("#author_id").value,
    genre: form.querySelector("#genre_id").value,
    format: form.querySelector("#format_id").value,
    quantity: form.querySelector("#stock_quantity").value,
    price: form.querySelector("#price").value,
    description: form.querySelector("#description").value,
    cover: form.querySelector("#cover_image").files[0] || null,
  };

  const idInput = form.querySelector("#id");
  if (idInput && idInput.value) {
    data.id = idInput.value;
  }

  return data;
}

async function openForm(mode, data = {}) {
  // get form body
  const formBody = document.querySelector(".form-body");

  // empty it first
  formBody.innerHTML = "";

  // set form title
  const formTitle = document.querySelector(".form-operation-text");
  formTitle.textContent = mode === "add" ? "ADD BOOK" : "UPDATE BOOK";

  const formConfigs = bookFormConfigs;

  const form = buildBookForm(mode, formConfigs);
  formBody.append(form);

  await populateBookFormSelects(form);

  if (mode === "edit") {
    hydrateBookForm(form, data);
  }

  swapClass(formContainer, "slide-in-form", "slide-out-form");
}

export function resetBookForm() {
  handleEntityImageElement("reset");
}
