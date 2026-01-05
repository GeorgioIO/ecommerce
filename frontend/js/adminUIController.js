import {
  showBookAddForm,
  showBookEditForm,
  collectBookFormData,
  loadBooks,
  resetBookForm,
} from "./books/booksUI.js";
import {
  addBook_DB,
  update_book_DB,
  deleteBook_DB,
} from "./books/booksService.js";
import { validateBookData } from "./books/booksValidators.js";
import {
  showAuthorAddForm,
  showAuthorEditForm,
  collectAuthorFormData,
  loadAuthors,
  resetAuthorForm,
} from "./authors/authorsUI.js";
import {
  addAuthor_DB,
  update_author_DB,
  delete_Author_DB,
} from "./authors/authorServices.js";
import { validateAuthorData } from "./authors/authorValidators.js";
import { showDeletionModal } from "./UIhelpers.js";
import {
  validateIDEligibility,
  handleImageFormat,
  handleEntityImageElement,
} from "./helpers.js";
import { swapClass } from "./helpers.js";
import { showMessageLog } from "./messageLog/messageLog.js";
import {
  showGenreAddForm,
  showGenreEditForm,
  collectGenreFormData,
  loadGenres,
  resetGenreForm,
} from "./genres/genresUI.js";
import {
  addGenre_DB,
  updateGenre_DB,
  deleteGenre_DB,
} from "./genres/genreServices.js";
import { validateGenreData } from "./genres/genreValidators.js";

const confirmationModal = document.querySelector("#confirmation-modal");
const closeOperationFormButton = document.querySelector(
  "#close-operation-form"
);
const formBody = document.querySelector(".form-body");
const formContainer = document.querySelector(".form-container");
const entityHandlers = {
  book: {
    showAdd: showBookAddForm,
    showEdit: showBookEditForm,
    resetForm: resetBookForm,
    addEntity: addBook_DB,
    updateEntity: update_book_DB,
    delete: deleteBook_DB,
    loader: loadBooks,
    dataCollector: collectBookFormData,
    dataValidator: validateBookData,
  },
  author: {
    showAdd: showAuthorAddForm,
    showEdit: showAuthorEditForm,
    resetForm: resetAuthorForm,
    addEntity: addAuthor_DB,
    updateEntity: update_author_DB,
    delete: delete_Author_DB,
    loader: loadAuthors,
    dataCollector: collectAuthorFormData,
    dataValidator: validateAuthorData,
  },
  genre: {
    showAdd: showGenreAddForm,
    showEdit: showGenreEditForm,
    resetForm: resetGenreForm,
    addEntity: addGenre_DB,
    updateEntity: updateGenre_DB,
    delete: deleteGenre_DB,
    loader: loadGenres,
    dataCollector: collectGenreFormData,
    dataValidator: validateGenreData,
  },
};

closeOperationFormButton.addEventListener("click", () => {
  formBody.innerHTML = "";
  swapClass(formContainer, "slide-out-form", "slide-in-form");
});

// Listening to change events
document.addEventListener("change", (e) => {
  const inputFile = e.target.closest('input[type="file"');

  if (inputFile) {
    const file = inputFile.files[0];
    handleImageFormat(file);
    handleEntityImageElement("set", file);
  }
});

document.addEventListener("reset", (e) => {
  const form = e.target;
  const entity = form.dataset.entity;
  const formResetter = entityHandlers?.[entity]?.resetForm;

  if (formResetter) {
    formResetter(form);
  }
});

document.addEventListener("click", async (e) => {
  const openOperationFormButton = e.target.closest(".open-operation-form");
  const showDeletionModalButton = e.target.closest(".show-confirmation-modal");

  if (openOperationFormButton) {
    const { entity, mode, id, intent } = openOperationFormButton.dataset;
    const openForm = entityHandlers?.[entity]?.[intent];
    if (openForm) {
      await openForm(id);
    }
  }

  if (showDeletionModalButton) {
    const { entity, id } = showDeletionModalButton.dataset;

    showDeletionModal(entity, id);
    return;
  }
});

confirmationModal.addEventListener("click", async (e) => {
  const closeConfirmationModal = e.target.closest("#close-confirmation-modal");
  const confirmBookDeletion = e.target.closest("#delete-entity-btn");

  if (closeConfirmationModal) {
    swapClass(confirmationModal, "fade-out-modal", "fade-in-modal");
    confirmationModal.querySelector("#delete-entity-btn").dataset.id = "";
    confirmationModal.querySelector("#delete-entity-btn").dataset.entity = "";

    setTimeout(() => {
      confirmationModal.querySelector(".confirmation-text").textContent = "";
    }, 1000);
  } else if (confirmBookDeletion) {
    try {
      const { entity, id, intent } = confirmBookDeletion.dataset;
      // Validate ID
      const validateID = validateIDEligibility(id);

      if (validateID.valid === false) {
        showMessageLog("error", validateID.error);
        return;
      }

      const deleteEntity = entityHandlers?.[entity]?.[intent];
      const loadEntityElements = entityHandlers?.[entity]?.loader;
      if (deleteEntity) {
        const deleteEntityResult = await deleteEntity(id);
        console.log(deleteEntityResult);
        if (!deleteEntityResult?.success) {
          showMessageLog("error", deleteEntityResult.message);
        } else {
          showMessageLog("success", deleteEntityResult.message);
          swapClass(confirmationModal, "fade-out-modal", "fade-in-modal");
          await loadEntityElements();
        }
      }
    } catch (err) {
      console.log(err);
    }
  }
});

/*

Listener responsible to hear submits on form
Work based on the entity of the form
two modes add and edit

*/
document.addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const { entity, mode } = form.dataset;
  // Get data collector and collect - entity
  const entityDataCollector = entityHandlers?.[entity]?.dataCollector;
  const data = entityDataCollector(form);

  // Get data validator and validate - entity
  const entityDataValidator = entityHandlers?.[entity]?.dataValidator;
  const validationResult = entityDataValidator(data);

  if (!validationResult.valid) {
    showMessageLog("error", validationResult.error);
    return;
  }

  // Get entity loader
  const loadEntityElements = entityHandlers?.[entity]?.loader;

  // if MODE is ADD
  if (mode === "add") {
    const addEntity = entityHandlers?.[entity]?.addEntity;

    const addEntityResult = await addEntity(data);

    if (addEntityResult?.success) {
      showMessageLog("success", addEntityResult.message);
      await loadEntityElements();
    } else {
      showMessageLog("error", addEntityResult.message);
    }
    // if MODE is EDIT (UPDATE)
  } else if (mode === "edit") {
    const updateEntity = entityHandlers?.[entity]?.updateEntity;

    const updateEntityResult = await updateEntity(data);
    if (updateEntityResult?.success) {
      showMessageLog("success", updateEntityResult.message);
      await loadEntityElements();
    } else {
      showMessageLog("error", updateEntityResult.message);
    }
  }
});
