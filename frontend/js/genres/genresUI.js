import { fetch_genres_DB } from "./genreServices.js";

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
