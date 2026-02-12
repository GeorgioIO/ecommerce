import {
  validateUserPassword,
  validateUserPhoneNumber,
  validateUseremail,
  validateUsername,
} from "../core/validators/registrationValidators.js";

import {
  createMesssageBox,
  appendMessageBox,
  activateMessageBox,
} from "../components/messageBox.js";

const registerForm = document.querySelector("#register-form");
const loginForm = document.querySelector("#log-in-form");

loginForm.addEventListener("submit", (e) => {
  const errorMessage = loginForm.querySelector(".error-message");
  errorMessage.textContent = "";

  // Collect Data
  let email = loginForm.querySelector("#useremail");
  let password = loginForm.querySelector("#password");

  // Sanitize Data
  email = identifier.value.trim();
  password = password.value.trim();

  activateMessageBox();

  if (!email && !password) {
    e.preventDefault();
    const messageBox = createMesssageBox("All fields are required");
    appendMessageBox(messageBox);
    return;
  }

  const emailValidation = validateUseremail(email);
  if (!emailValidation.valid) {
    e.preventDefault();
    const messageBox = createMesssageBox(emailValidation.error);
    appendMessageBox(messageBox);
    return;
  }
});

registerForm.addEventListener("submit", (e) => {
  const errorMessage = registerForm.querySelector(".error-message");
  errorMessage.textContent = "";

  // Collect Data
  let name = registerForm.querySelector("#register-username");
  let email = registerForm.querySelector("#register-email");
  let phone = registerForm.querySelector("#register-phone");
  let password = registerForm.querySelector("#register-password");

  // Sanitize Data
  name = name.value.trim();
  email = email.value.trim();
  phone = phone.value.replace(/\s+/g, "");
  password = password.value.trim();

  activateMessageBox();

  // Validate Data
  const nameValidation = validateUsername(name);
  if (!nameValidation.valid) {
    e.preventDefault();
    const messageBox = createMesssageBox(nameValidation.error);
    appendMessageBox(messageBox);
    return;
  }

  const emailValidation = validateUseremail(email);
  if (!emailValidation.valid) {
    e.preventDefault();
    const messageBox = createMesssageBox(emailValidation.error);
    appendMessageBox(messageBox);
    return;
  }

  const phoneValidation = validateUserPhoneNumber(phone);
  if (!phoneValidation.valid) {
    e.preventDefault();
    const messageBox = createMesssageBox(phoneValidation.error);
    appendMessageBox(messageBox);
    return;
  }

  const passwordValidation = validateUserPassword(password);
  if (!passwordValidation.valid) {
    e.preventDefault();
    const messageBox = createMesssageBox(passwordValidation.error);
    appendMessageBox(messageBox);
    return;
  }
});
