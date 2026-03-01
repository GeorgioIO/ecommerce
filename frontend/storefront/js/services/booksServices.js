export async function searchBooks(value) {
  const result = await fetch("../../../backend/books/search_books.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      value: value,
    }),
  });

  if (!result.ok) {
    throw new Error("Search request failed");
  }

  return result.json();
  // console.log(result.text());
}

export async function getBooks_DB(filters = null, pagination = null) {
  let params = "";

  if (pagination) {
    params = new URLSearchParams({
      page: pagination.page,
      perPage: pagination.perPage,
    });
  }

  if (filters) {
    Object.keys(filters).forEach((key) => {
      if (filters[key] === "--" || filters[key] === "") return;
      params.set(key, filters[key]);
    });
  }

  const result = await fetch(
    `../../../backend/books/get_books_storefront.php?${params.toString()}`,
  );

  return result.json();
  // console.log(result.text());
}

export async function getBooksStats_DB() {
  const result = await fetch("../../../backend/books/get_books_stats.php");

  return result.json();
}

export async function getGenres_DB() {
  const result = await fetch("../../../backend/genres/get_genres.php");

  return result.json();
}

export async function getAuthors_DB() {
  const result = await fetch("../../../backend/authors/get_authors.php");

  return result.json();
}

export async function getFormats_DB() {
  const result = await fetch("../../../backend/formats/get_formats.php");

  return result.json();
  // console.log(result.text());
}
