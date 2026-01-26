import { fetch_books_DB } from "../books/booksService.js";
import { showMessageLog } from "../messageLog/messageLog.js";
import {
  createOrderLine,
  hydrateProductLine,
  appendOrderLine,
  handleOrderLinePriceChange,
  handleTotalOrderLinesPrice,
} from "./orderLines.js";

const formContainer = document.querySelector(".form-container");
const closeArrow = `
<svg fill="#000000" width="25px" height="25px" viewBox="0 0 24 24" class="right-arrow" xmlns="http://www.w3.org/2000/svg">
<path id="primary" d="M21,12H3M6,9,3,12l3,3" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 1.5;"></path>
</svg>
`;

// LISTENERS

document.addEventListener("click", (e) => {
  const closeSearchBoxButton = e.target.closest("#close-search-box-button");

  if (closeSearchBoxButton) {
    removeSearchBox();
  }
});

// ========== EXPORTED FUNCTIONS ========== //

export function removeSearchBox() {
  const searchBox = document.querySelector(".inline-book-search") || null;
  if (searchBox) {
    searchBox.classList.add("slide-inline-search-box-out");

    setTimeout(() => {
      searchBox.remove();
    }, 500);
  }
}

export function enableSearch(input, searchBox) {
  const searchesBody = searchBox.querySelector(
    ".inline-book-searches-container",
  );
  input.addEventListener("input", async (e) => {
    // Get value
    let searchValue = input.value.trim().toLowerCase();

    // Empty box
    searchesBody.innerHTML = "";

    if (searchValue.length < 2) return;

    const books = await fetch_books_DB();

    // Find matches
    const matches = books.data.filter((book) =>
      book.title.toLowerCase().includes(searchValue),
    );

    matches.forEach((book) => {
      const searchLine = createSearchLine(book);
      searchesBody.append(searchLine);

      searchLine.addEventListener("click", (e) => {
        handleInlineBookSearchSelection(
          book,
          input.closest(".order-lines-table-line"),
        );
      });
    });
  });
}

export function showProductSearch(cell) {
  const main = document.querySelector("main");

  // Create text input
  const searchInput = document.createElement("input");
  searchInput.name = "searched-book";
  searchInput.placeholder = "Search Book...";
  searchInput.classList.add("search-inline-book");

  // Create search box
  let searchBox = document.querySelector(".inline-book-search");

  // Search Box not already there
  if (!searchBox) {
    searchBox = createSearchBox();
    main.append(searchBox);
  }

  cell.innerHTML = "";
  cell.append(searchInput);

  // enable search in box
  enableSearch(searchInput, searchBox);
}

// ========== LOCAL FUNCTIONS ========== //

function createSearchBox() {
  // Create box
  const box = document.createElement("div");
  box.classList.add("inline-book-search");

  // Create header
  const boxHeader = document.createElement("div");
  boxHeader.classList.add("inline-book-search-header");

  const closeButton = document.createElement("button");
  closeButton.id = "close-search-box-button";
  closeButton.innerHTML = closeArrow;

  boxHeader.append(closeButton);

  const boxSearchBody = document.createElement("div");
  boxSearchBody.classList.add("inline-book-searches-container");

  box.append(boxHeader, boxSearchBody);

  return box;
}

function handleInlineBookSearchSelection(book, orderLine) {
  /* 
        States of selecting a book in inline search :
        A : A line exist and book doesnt exist yet -> hydrate the line normally -- DONE
        B : The book itself exist , only increase the quantity by one -- DONE
        C : A line doesnt exist , append the line and hydrate it directly -- DONE
        D : The book is out of stock , Block the operation send an error message -- DONE
        E : Empty line exist but book clicked without search -- DONE
    */

  // D : NO STOCK
  if (book.stock_quantity === 0) {
    showMessageLog("error", "This book is out of stock");
    return;
  }

  // B : EXISTING LINE
  const existingLine = findOrderLineByID(book);
  if (existingLine) {
    // increase quantity by one
    increaseOrderInlineQuantity(existingLine);
    handleOrderLinePriceChange(existingLine);
    handleTotalOrderLinesPrice();
    return;
  }

  // E : EMPTY LINE , NO SEARCH
  const lineNoID = findLineNoID();
  if (lineNoID) {
    hydrateProductLine(book, lineNoID);
    handleTotalOrderLinesPrice();
    return;
  }

  // A : EMPTY LINE , NONE EXISTING BOOK
  if (orderLine && !orderLine.dataset.bookid) {
    hydrateProductLine(book, orderLine);
    handleTotalOrderLinesPrice();
    return;
  }

  // C : NO EMPTY LINE
  const newLine = createOrderLine();
  appendOrderLine(newLine);
  hydrateProductLine(book, newLine);
  handleTotalOrderLinesPrice();
}

function increaseOrderInlineQuantity(line) {
  const quantityInput = line.querySelector(".quantity-line-input");
  quantityInput.value = parseInt(quantityInput.value) + 1;
}

function findLineNoID() {
  const lines = formContainer.querySelectorAll(".order-lines-table-line");
  return [...lines].find((line) => !line.dataset.bookid) || null;
}

function findOrderLineByID(book) {
  const lines = formContainer.querySelectorAll(".order-lines-table-line");
  return [...lines].find((line) => line.dataset.bookid == book.id) || null;
}

function createSearchLine(item) {
  const searchLine = document.createElement("div");
  searchLine.classList.add("inline-search-line");

  searchLine.innerHTML = `      
    <img class="book-search-image" src="../../assets/images/${item.cover_image}" alt="${item.title}">
    <div class="book-search-metadata-container">
      <div>
        <h3> ${item.title} </h3>
        <p> By <strong  class="author-inline-name">${item.author_name}</strong> , ${item.language} </p>
        <p> Format: ${item.format} </p>
        <p> <strong> $${item.price} </strong> </p>
      </div>
      <p> ${item.description} </p>
    </div>
      `;

  return searchLine;
}
