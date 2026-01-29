/*

Function responsible of building the template of the collections bar

*/
export function buildCollectionsBar() {
  let genresContainer =
    document.querySelector(".genres-sidebar-container") ?? null;

  if (genresContainer) {
    genresContainer.remove;
  }

  genresContainer = document.createElement("div");
  genresContainer.classList.add("genres-sidebar-container");

  const genresList = document.createElement("ul");

  const backItem = document.createElement("li");
  backItem.classList.add("close-collection-li");
  const backButton = document.createElement("button");
  backButton.type = "button";
  backButton.id = "close-genres-sidebar-button";

  backButton.innerHTML = `
    <svg class="left-caret" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="none" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
    </svg>
    Back
    `;

  backItem.append(backButton);

  genresList.append(backItem);

  genresContainer.append(genresList);

  return genresContainer;
}

export function populateCollectionsBar(container, genres) {
  const genresList = container.querySelector(".genres-sidebar-container ul");

  genres.data.forEach((genre) => {
    const genreItem = document.createElement("li");
    const genreAnchor = document.createElement("a");

    genreAnchor.textContent = genre.name;
    genreItem.append(genreAnchor);

    genresList.append(genreItem);
  });
}
