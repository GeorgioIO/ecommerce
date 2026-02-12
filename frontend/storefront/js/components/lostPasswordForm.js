import {
  createMesssageBox,
  appendMessageBox,
  activateMessageBox,
} from "./messageBox.js";

import {
  validateUseremail,
  validateUserPassword,
} from "../core/validators/registrationValidators.js";

const form = document.querySelector("#lost-password-form") ?? null;
if (form) {
  form.addEventListener("submit", (e) => {
    const email = form.querySelector("#lost-pass-email");

    const emailValidation = validateUseremail(email.value);

    if (!emailValidation.valid) {
      e.preventDefault();
      activateMessageBox();
      const messageBox = createMesssageBox(emailValidation.error);
      appendMessageBox(messageBox);
    }
  });
}
