// Function responsible to get data for a single book
export async function get_book_data_DB(book_id) {
  const result = await fetch("../../backend/books/get_book.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: book_id,
    }),
  });

  return result.json();
  // console.log(result.text());
}

// Function responsible for adding a book to the database
export async function addBook_DB(bookData) {
  const formData = new FormData();
  formData.append("isbn", bookData.isbn);
  formData.append("sku", bookData.sku);
  formData.append("title", bookData.title);
  formData.append("language", bookData.language);
  formData.append("author", bookData.author);
  formData.append("description", bookData.description);
  formData.append("genre", bookData.genre);
  formData.append("format", bookData.format);
  formData.append("quantity", bookData.quantity);
  formData.append("price", bookData.price);
  formData.append("cover", bookData.cover);

  const result = await fetch("../../backend/books/add_book.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

// Function responsible for deleting a book from the database
export async function deleteBook_DB(book_ID) {
  const result = await fetch("../../backend/books/delete_book.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: book_ID,
    }),
  });

  return result.json();
}

export async function update_book_DB(bookData) {
  const formData = new FormData();

  formData.append("id", bookData.id);
  formData.append("isbn", bookData.isbn);
  formData.append("sku", bookData.sku);
  formData.append("title", bookData.title);
  formData.append("language", bookData.language);
  formData.append("author", bookData.author);
  formData.append("description", bookData.description);
  formData.append("genre", bookData.genre);
  formData.append("format", bookData.format);
  formData.append("quantity", bookData.quantity);
  formData.append("price", bookData.price);
  formData.append("cover", bookData.cover);

  const result = await fetch("../../backend/books/update_book.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

export async function fetch_books_DB(filters, pagination = null) {
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

  const result = await fetch(
    `../../backend/books/get_books.php?${params.toString()}`,
  );

  return result.json();
  // console.log(result.text());
}
