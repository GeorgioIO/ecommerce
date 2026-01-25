export function createPaginationButtons(pagination) {
  let buttons = "";
  let i = 1;
  do {
    buttons += `<button data-page="${i}" class="page-button"> ${i} </button>`;
    i++;
  } while (i <= pagination.totalPages);

  buttons = `
    <button class="page-button" id="previous-page-button"> &lt; </button>
      ${buttons}
    <button class="page-button" id="next-page-button"> &gt; </button>
    `;

  return buttons;
}
