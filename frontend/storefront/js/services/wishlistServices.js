export async function addToWishlist(productID) {
  const result = await fetch("../../../backend/wishlist/add_wishlist.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: productID,
    }),
  });

  return result.json();
}

export async function removeFromWishlist(productID) {
  const result = await fetch("../../../backend/wishlist/remove_wishlist.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: productID,
    }),
  });

  console.log(productID);
  document.dispatchEvent(
    new CustomEvent("wishlistUpdated", {
      detail: { id: productID },
    }),
  );

  return result.json();
  // console.log(result.text());
}

export async function getWishlistItems(pagination = null) {
  let params = "";

  if (pagination) {
    params = new URLSearchParams({
      page: pagination.page,
      perPage: pagination.perPage,
    });
  }

  const result = await fetch(
    `../../../backend/wishlist/get_wishlist.php?${params.toString()}`,
  );

  if (!result.ok) return;

  return result.json();
  // console.log(result.text());
}
