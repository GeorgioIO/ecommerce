import {
  createMesssageBox,
  appendMessageBox,
  activateMessageBox,
} from "./messageBox.js";

import { validateUserPassword } from "../core/validators/registrationValidators.js";

const form = document.querySelector("#reset-password-form") ?? null;
console.log(form);
if (form) {
  form.addEventListener("submit", (e) => {
    const password = form.querySelector("#new-password");
    const confirmPassword = form.querySelector("#confirm-password");

    const passwordValidation = validateUserPassword(password.value);

    if (password.value !== confirmPassword.value) {
      e.preventDefault();
      activateMessageBox();
      const messageBox = createMesssageBox("Password do not match");
      appendMessageBox(messageBox);
    } else if (!passwordValidation.valid) {
      e.preventDefault();
      activateMessageBox();
      const messageBox = createMesssageBox(passwordValidation.error);
      appendMessageBox(messageBox);
    }
  });
}
