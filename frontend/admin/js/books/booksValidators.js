export function validateBookData(data) {
  // Title
  const title = data.title.trim();
  if (!title || title === "") {
    return {
      valid: false,
      error: "Error in Title : title cannot be empty",
    };
  }

  // ISBN
  const isbn = data.isbn.trim();
  if (!isbn || (isbn.length != 13 && isbn.length != 10)) {
    return {
      valid: false,
      error: "Error in ISBN: ISBN must be either 13 digits or 10.",
    };
  }

  // Sku
  const sku = data.sku.trim();
  if (!sku || sku == "") {
    return {
      valid: false,
      error: "Error in Sku: Sku cannot be empty",
    };
  }

  // Author , Genre & Format (IDs)
  const authorID = parseInt(data.author);
  const genreID = parseInt(data.genre);
  const formatID = parseInt(data.format);

  if (
    Number.isNaN(genreID) ||
    Number.isNaN(authorID) ||
    Number.isNaN(formatID)
  ) {
    return {
      valid: false,
      error: "Error in Genre , Author or Format : Invalid value",
    };
  }

  // Cover
  const cover = data.cover;

  if (cover) {
    if (!cover.type.startsWith("image/")) {
      return {
        valid: false,
        error: "Error in cover : Cover must be an image file",
      };
    }

    const allowedTypes = ["image/png", "image/jpeg"];

    if (!allowedTypes.includes(cover.type)) {
      return {
        valid: false,
        error: "Error in cover : Only PNG and JPG, JPEG formats are allowed",
      };
    }
  }

  const language = data.language ?? "Not Defined";

  if (
    language !== "Not Defined" &&
    language !== "English" &&
    language !== "French"
  ) {
    return {
      valid: false,
      error: "Error in language : Invalid Language",
    };
  }

  // Price
  const price = parseFloat(data.price);

  if (Number.isNaN(price) || price < 0) {
    return {
      valid: false,
      error: "Error in Price : price must be a number and not below 0",
    };
  }

  // Quantity
  const quantity = parseInt(data.quantity);

  if (Number.isNaN(quantity) || quantity < 0) {
    return {
      valid: false,
      error: "Error in Quantity : quantity must be a number and not below 0",
    };
  }

  return {
    valid: true,
    error: "",
  };
}
