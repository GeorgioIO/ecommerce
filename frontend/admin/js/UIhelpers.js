import { createPaginationButtons } from "./pagination/paginationUI.js";
import { entityHandlers } from "./adminUIController.js";
import { listState } from "./adminUIController.js";

export function toggleDiscountInfo() {
  const disountInfo = document.querySelectorAll(".discount-info");

  disountInfo.forEach((infoContainer) => {
    infoContainer.classList.toggle("show");
  });
}

export function handlePaginationButtonsColor(pageNumber) {
  const paginationsButtons = document.querySelectorAll(".page-button");
  paginationsButtons.forEach((button) => {
    if (
      button.id !== "previous-page-button" &&
      button.id !== "next-page-button"
    ) {
      button.classList.remove("active-page-button");
    }
  });

  const targetPageButton = document.querySelector(
    `.page-button[data-page="${pageNumber}"]`,
  );

  if (!targetPageButton) return;

  targetPageButton.classList.add("active-page-button");
}

export function toggleButtonClickablility(button, clickable) {
  if (clickable) {
    swapClass(button, "not-clickable", "clickable");
  } else {
    swapClass(button, "clickable", "not-clickable");
  }
}

export function swapClass(element, classA, classR) {
  element.classList.remove(classR);
  element.classList.add(classA);
}

export function handleEntityImageElement(mode = "set", source = "") {
  const imageEmptyText = document.querySelector(".empty-image-text");
  const image = document.querySelector(".entity-image-display");

  if (mode === "reset") {
    image.src = "";
    imageEmptyText.style.display = "flex";
    image.style.display = "none";
    return;
  }

  if (mode === "set") {
    if (!source) return;

    imageEmptyText.style.display = "none";
    image.style.display = "block";

    if (source instanceof File) {
      image.src = URL.createObjectURL(source);
      return;
    }

    if (typeof source === "string") {
      image.src = "../../assets/images/" + source;
      return;
    }
  }
}

export function changeSidebarSection(entity) {
  const sidebarButtons = document.querySelectorAll(
    ".sidebar ul li .adm-sidebar-button",
  );

  sidebarButtons.forEach((button) => {
    button.classList.remove("active-sidebar-btn");
    button.querySelector("p").classList.remove("active-sidebar-text");

    if (button.dataset.section === entity) {
      button.classList.add("active-sidebar-btn");
      button.querySelector("p").classList.add("active-sidebar-text");
    }
  });
}

function renderTableFooter(paginationData) {
  const buttons = createPaginationButtons(paginationData);
  return `
  <div class="flex-table-footer">
    ${buttons}
  </div>
  `;
}

/*
renderEmptyState : responsible of controlling the dom of the table in table-container , based on entity (books, authors, genres....)
Input : 
    - entity (books, authors, genres)
    - label : how entity must be displayed on Screen ex : Books, Authors
    - canAdd : weither the entity can be added
Output : empty table state in HTML
*/
export function renderEmptyTableState({ entity, label, canAdd = true }) {
  return `
      <div class="empty-state-container">
        <p>Currently there is no ${entity} ${canAdd ? "- click to add" : ""}</p>
        ${
          canAdd
            ? `
            <button class="open-operation-form" data-mode="add" data-entity="${entity}" data-intent="showAdd">
                Add New ${label}
            </button>
            `
            : ""
        }
      </div>
  `;
}

/*
renderActiveTableState : responsible for rendering the full table of an entity , based on on its params
Input :
    - entity
    - label
    - data
    - renderHeader() -> function responsible of rendering table header
    - renderRow(item) -> function responsible of rendering a single row
    - canAdd -> weither the entity can be added
Output : populate the table with content
*/
export function renderActiveTableState({
  entity,
  label,
  data,
  pagination,
  renderHeader,
  renderRow,
  canAdd = true,
}) {
  const header = renderHeader();
  const footer = renderTableFooter(pagination);
  const showDeleted = entityHandlers[entity].showDeleted;

  const deletedCheckbox =
    entity !== "order" && entity !== "customer"
      ? `<div class="show-deleted-entity-filter">
                <input type="checkbox" id="show-deleted-entity-checkbox" data-entity="${entity}" ${showDeleted ? "checked" : ""}/>
                <label for="show-deleted-entity-checkbox"> Show deleted </label>
            </div>`
      : "";

  return `
        <div class="table-container-header">
            ${
              canAdd
                ? `
                <button class="open-operation-form" data-mode="add" data-intent="showAdd" data-entity="${entity}">
                    Add New ${label}
                </button>
                `
                : ""
            }
            ${deletedCheckbox}
        </div>
        
        <div class="flex-table" data-entity="${entity}">
            ${header}
            <div class="flex-table-body">    
                ${data.map((item) => renderRow(item)).join("")}
            </div>
            ${footer}
        </div>
        `;
}

export function renderMiniActiveTableState(data, renderHeader, renderRow) {
  const header = renderHeader();

  return `
    <div class="mini-flex-table">
      ${header}
      <div class="mini-flex-table-body">
        ${data.map((item) => renderRow(item)).join("")}
      </div>
    </div>  
  `;
}

export function showDeletionModal(entity, id, mode) {
  let message =
    mode === "delete"
      ? `Are you sure you want to delete ${entity} with ID #${id} ?`
      : `Are you sure you want to restore ${entity} with ID #${id}`;
  let buttonID =
    mode === "delete" ? `${mode}-entity-btn` : `${mode}-entity-button`;

  const confirmationModal = document.querySelector("#confirmation-modal");
  confirmationModal.style.display = "flex";

  const confirmationText =
    confirmationModal.querySelector(".confirmation-text");
  const confirmDeletionButton = confirmationModal.querySelector(
    ".confirm-delete-or-restore-button",
  );

  confirmationText.textContent = message;
  confirmDeletionButton.id = buttonID;
  confirmDeletionButton.dataset.id = id;
  confirmDeletionButton.dataset.entity = entity;
  confirmDeletionButton.dataset.intent = mode;
  swapClass(confirmationModal, "fade-in-modal", "fade-out-modal");
}
