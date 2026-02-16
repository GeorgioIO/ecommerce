export function validateAddressID(id) {
  if (Number.isInteger(parseInt(id)) != true) {
    return {
      valid: false,
      error: "Error in ID : there is a problem with the id",
    };
  }
  return {
    valid: true,
    error: "",
  };
}
