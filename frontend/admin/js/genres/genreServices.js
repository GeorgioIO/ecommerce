export async function getGenres_DB(filters, pagination = null) {
  let params = "";

  if (pagination) {
    params = new URLSearchParams({
      page: pagination.page,
      perPage: pagination.perPage,
    });
  }

  if (filters) {
    const key = Object.keys(filters)[0];
    params.set(key, filters[key]);
  }

  const res = await fetch(
    `../../backend/genres/get_genres.php?${params.toString()}`,
  );

  return res.json();
}

export async function addGenre_DB(genreData) {
  const formData = new FormData();
  formData.append("name", genreData.name);
  formData.append("image", genreData.image);

  const res = await fetch("../../backend/genres/add_genre.php", {
    method: "POST",
    body: formData,
  });

  return res.json();
}

export async function updateGenre_DB(genreData) {
  const formData = new FormData();
  formData.append("id", genreData.id);
  formData.append("name", genreData.name);
  formData.append("image", genreData.image);

  const res = await fetch("../../backend/genres/update_genre.php", {
    method: "POST",
    body: formData,
  });

  return res.json();
}

export async function deleteGenre_DB(genreID) {
  const res = await fetch(
    `../../backend/genres/delete_genre.php?id=${genreID}`,
    {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return res.json();
}

export async function restoreGenre_DB(genreID) {
  const res = await fetch(
    `../../backend/genres/restore_genre.php?id=${genreID}`,
    {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return res.json();
}

export async function getGenre_DB(genreID) {
  const res = await fetch(`../../backend/genres/get_genre.php?id=${genreID}`);

  return res.json();
}
