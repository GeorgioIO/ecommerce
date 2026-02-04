export async function getGenres_DB() {
  const result = await fetch("../../../backend/genres/get_genres.php");

  return result.json();
}

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
