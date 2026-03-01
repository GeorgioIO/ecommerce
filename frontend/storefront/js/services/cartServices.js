export async function addToCart_DB(bookID, quantity = 1) {
  const formData = new FormData();
  formData.append("book_id", bookID);
  formData.append("quantity", quantity);
  const result = await fetch("../../../backend/cart/add_to_cart.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

export async function removeFromCart_DB(bookID) {
  const formData = new FormData();
  const result = await fetch("../../../backend/cart/remove_from_cart.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}
