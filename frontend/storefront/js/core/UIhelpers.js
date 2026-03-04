export function createCartButton(className, type = null) {
  let svgFill = "none";

  const cartButton = document.createElement("button");
  if (type === "cart") {
    svgFill = "black";
    cartButton.dataset.state = "active";
  }
  cartButton.classList.add(className);
  cartButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="${svgFill}" viewBox="0 0 24 24">
        <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.3 5H21l-2 7H7.377M20 16H8L6 3H3m6 17a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm11 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
      </svg>
  `;

  return cartButton;
}
