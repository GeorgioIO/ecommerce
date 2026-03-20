import {
  getAuthors_DB,
  getBooksStats_DB,
  getFormats_DB,
  getGenres_DB,
} from "../../services/booksServices.js";

export async function populateFilteringBar() {
  // Author , genres , formats
  const genreSelector = document.querySelector("#genre");
  await populateGenresSelector(genreSelector);

  const authorSelector = document.querySelector("#author");
  await populateAuthorsSelector(authorSelector);

  const formatSelector = document.querySelector("#format");
  await populateFormatsSelector(formatSelector);
}

export function populateExistingFilters(filters) {
  const form = document.querySelector("#filtering-form") ?? null;

  if (!form || !filters) return;

  Object.keys(filters).forEach((filter) => {
    if (filter === "stock") {
      if (filters[filter] === 1)
        form.querySelector("#available-checkbox").checked = true;
      if (filters[filter] === 0)
        form.querySelector("#outofstock-checkbox").checked = true;
    }

    const input = form.querySelector(`#${filter}`) ?? null;

    if (input) {
      input.value = filters[filter];
    }
  });
}

async function populateFormatsSelector(selector) {
  const formats = await getFormats_DB();
  formats.data.forEach((format) => {
    const optionElement = document.createElement("option");
    optionElement.textContent = format.name;
    optionElement.value = format.id;

    selector.append(optionElement);
  });
}

async function populateAuthorsSelector(selector) {
  const authors = await getAuthors_DB();
  authors.data.forEach((author) => {
    const optionElement = document.createElement("option");
    optionElement.textContent = author.name;
    optionElement.value = author.id;

    selector.append(optionElement);
  });
}

async function populateGenresSelector(selector) {
  const genres = await getGenres_DB();

  genres.data.forEach((genre) => {
    const optionElement = document.createElement("option");
    optionElement.textContent = genre.name;
    optionElement.value = genre.id;

    selector.append(optionElement);
  });
}
