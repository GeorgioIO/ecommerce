// Function responsible for adding a author to the database
export async function addAuthor_DB(authorData) {
  const formData = new FormData();
  formData.append("name", authorData.name);

  const result = await fetch("../../backend/authors/add_author.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

// Function responsible for deleting a author from the database
export async function delete_Author_DB(author_ID) {
  const result = await fetch("../../backend/authors/delete_author.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: author_ID,
    }),
  });

  return result.json();
}

export async function update_author_DB(authorData) {
  const formData = new FormData();

  formData.append("id", authorData.id);
  formData.append("name", authorData.name);

  const result = await fetch("../../backend/authors/update_author.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

// Function responsible to get data for a single book
export async function get_author_data_DB(author_id) {
  const res = await fetch("../../backend/authors/get_author.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: author_id,
    }),
  });

  return res.json();
}

export async function fetch_authors_DB() {
  const result = await fetch("../../backend/authors/get_authors.php");
  return result.json();
}
