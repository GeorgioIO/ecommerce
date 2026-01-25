export function createPaginationButtons(pagination) {
  let buttons = "";
  for (let i = 1; i <= pagination.totalPages; i++) {
    buttons += `<button data-page="${i}" class="page-button"> ${i} </button>`;
  }

  buttons = `
    <button class="page-button" id="previous-page-button"> &lt; </button>
      ${buttons}
    <button class="page-button" id="next-page-button"> &gt; </button>
    `;

  return buttons;
}
