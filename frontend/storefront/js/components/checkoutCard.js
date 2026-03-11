export function buildCheckoutCard(product) {
  const checkoutCard = document.createElement("div");
  checkoutCard.classList.add("checkout-card");
  checkoutCard.dataset.productid = product.book_id;

  // Figure
  const figure = document.createElement("figure");
  figure.classList.add("checkout-card-figure");
  const imageAnchorTag = document.createElement("a");
  imageAnchorTag.href = `../pages/product.php?slug=${product.slug}`;
  const image = document.createElement("img");
  image.src = "../../../assets/images/" + product.cover_image;
  image.alt = `${product.title} book cover`;
  imageAnchorTag.append(image);

  // Const quantity
  const quantityBadge = document.createElement("span");
  quantityBadge.classList.add("quantity-badge");
  quantityBadge.textContent = product.quantity;

  figure.append(imageAnchorTag, quantityBadge);

  const cardTitle = document.createElement("p");
  cardTitle.classList.add("checkout-card-title");
  cardTitle.textContent = product.title;

  const cardPrice = document.createElement("p");
  cardPrice.classList.add("checkout-card-price");
  cardPrice.textContent = `$${product.final_price}`;

  checkoutCard.append(figure, cardTitle, cardPrice);

  return checkoutCard;
}
