export async function addToCart_DB(bookID, quantity = 1) {
  const formData = new FormData();
  formData.append("book_id", bookID);
  formData.append("quantity", quantity);

  const result = await fetch("../../../backend/cart/add_to_cart.php", {
    method: "POST",
    body: formData,
  });

  const response = await result.json();

  if (response.success) {
    document.dispatchEvent(
      new CustomEvent("cartUpdated", {
        detail: { id: bookID },
      }),
    );
  }
  return response;
}

export async function removeFromCart_DB(bookID) {
  const formData = new FormData();
  formData.append("book_id", bookID);

  const result = await fetch("../../../backend/cart/remove_from_cart.php", {
    method: "POST",
    body: formData,
  });

  const response = await result.json();

  if (response.success) {
    document.dispatchEvent(
      new CustomEvent("cartUpdated", {
        detail: { id: bookID },
      }),
    );
  }

  return response;
}

export async function getCartItems() {
  const result = await fetch("../../../backend/cart/get_cart.php", {
    method: "GET",
  });

  // console.log(result.text());
  return result.json();
}
