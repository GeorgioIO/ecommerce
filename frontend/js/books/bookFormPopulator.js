import { populateSelectAuthors } from "../authors/authorsUI.js";
import { populateSelectFormats } from "../formats/formats.js";
import { populateSelectGenres } from "../genres/genresUI.js";
import { populateSelectLanguages } from "../languages/languages.js";

export async function populateBookFormSelects(form) {
  const authorSelect = form.querySelector("#author_id");
  const languageSelect = form.querySelector("#language");
  const genreSelect = form.querySelector("#genre_id");
  const formatSelect = form.querySelector("#format_id");

  await populateSelectAuthors(authorSelect);
  await populateSelectFormats(formatSelect);
  await populateSelectGenres(genreSelect);
  populateSelectLanguages(languageSelect);
}
