// Get book
export async function getBook_DB(bookID) {
  const result = await fetch(`../../backend/books/get_book.php?id=${bookID}`);

  return result.json();
}

// Add book
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
  formData.append("is_on_sale", bookData.isOnSale);
  formData.append("discount_percentage", bookData.discountPercentage);

  const result = await fetch("../../backend/books/add_book.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
  // console.log(result.text());
}

export async function deleteBook_DB(bookID) {
  const result = await fetch(
    `../../backend/books/delete_book.php?id=${bookID}`,
    {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return result.json();
  // console.log(result.text());
}

export async function restoreBook_DB(bookID) {
  const result = await fetch(
    `../../backend/books/restore_book.php?id=${bookID}`,
    {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return result.json();
  // console.log(result.text());
}

export async function updateBook_DB(bookData) {
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
  formData.append("is_on_sale", bookData.isOnSale);
  formData.append("discount_percentage", bookData.discountPercentage);

  const result = await fetch("../../backend/books/update_book.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
  // console.log(result.text());
}

export async function getBooks_DB(filters, pagination = null) {
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

  // console.log(result.text());

  return result.json();
}
