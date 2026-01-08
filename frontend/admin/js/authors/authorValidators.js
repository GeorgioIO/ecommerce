export function validateAuthorData(data) {
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

  return {
    valid: true,
    error: "",
  };
}
