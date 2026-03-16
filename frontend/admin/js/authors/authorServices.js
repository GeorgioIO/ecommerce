// Add Author
export async function addAuthor_DB(authorData) {
  const res = await fetch("../../backend/authors/add_author.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(authorData),
  });

  return res.json();
}

// Delete Author
export async function deleteAuthor_DB(authorID) {
  const res = await fetch(
    `../../backend/authors/delete_author.php?id=${authorID}`,
    {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return res.json();
}

// Restore Author
export async function restoreAuthor_DB(authorID) {
  const res = await fetch(
    `../../backend/authors/restore_author.php?id=${authorID}`,
    {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
    },
  );

  return res.json();
}

export async function updateAuthor_DB(authorData) {
  const res = await fetch("../../backend/authors/update_author.php", {
    method: "UPDATE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(authorData),
  });

  return res.json();
}

// Get author
export async function getAuthor_DB(author_id) {
  const res = await fetch(
    `../../backend/authors/get_author.php?id=${author_id}`,
  );

  return res.json();
}

export async function getAuthors_DB(filters, pagination = null) {
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
    `../../backend/authors/get_authors.php?${params.toString()}`,
  );
  // console.log(res.text());
  return res.json();
}
