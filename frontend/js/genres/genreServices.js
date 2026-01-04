export async function fetch_genres_DB() {
  const result = await fetch("../backend/genres/load_genres.php");

  return result.json();
}

// Function responsible to add a genre
export async function addGenre_DB(genreData) {
  const formData = new FormData();
  formData.append("name", genreData.name);
  formData.append("image", genreData.image);

  const result = await fetch("../backend/genres/add_genre.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

// Function responsible for updating a genre
export async function updateGenre_DB(genreData) {
  const formData = new FormData();

  formData.append("id", genreData.id);
  formData.append("name", genreData.name);
  formData.append("image", genreData.image);

  const result = await fetch("../backend/genres/update_genre.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

export async function deleteGenre_DB(genreID) {
  const result = await fetch("../backend/genres/delete_genre.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: genreID,
    }),
  });

  console.log(result.text());
}

export async function getGenreData_DB(genreID) {
  const result = await fetch("../backend/genres/fetch_single_genre.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: genreID,
    }),
  });

  return result.json();
}
