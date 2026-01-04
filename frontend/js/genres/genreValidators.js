export function validateGenreData(data) {
  // Name
  const name = data.name.trim();
  if (!name || name === "") {
    return {
      valid: false,
      error: "Error in Name : Name cannot be empty",
    };
  }

  if (name.length > 45) {
    return {
      valid: false,
      error: "Error in Name : Name connect succeed 45 characters",
    };
  }

  // Image
  const image = data.image;

  if (image) {
    if (!image.type.startsWith("image/")) {
      return {
        valid: false,
        error: "Error in file : file must be an image file",
      };
    }

    const allowedTypes = ["image/png", "image/jpeg"];

    if (!allowedTypes.includes(cover.type)) {
      return {
        valid: false,
        error: "Error in image : Only PNG and JPG, JPEG formats are allowed",
      };
    }
  }

  return {
    valid: true,
    error: "",
  };
}
