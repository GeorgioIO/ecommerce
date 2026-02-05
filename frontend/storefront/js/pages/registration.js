import {
  validateUserPassword,
  validateUserPhoneNumber,
  validateUseremail,
  validateUsername,
} from "../core/validators/registrationValidators.js";

const registerForm = document.querySelector("#register-form");

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

  console.log(password);
  // Validate Data
  const nameValidation = validateUsername(name);
  if (!nameValidation.valid) {
    e.preventDefault();
    errorMessage.classList.toggle("hidden");
    errorMessage.textContent = nameValidation.error;
    return;
  }

  const emailValidation = validateUseremail(email);
  if (!emailValidation.valid) {
    e.preventDefault();
    errorMessage.classList.toggle("hidden");
    errorMessage.textContent = emailValidation.error;
    return;
  }

  const phoneValidation = validateUserPhoneNumber(phone);
  if (!phoneValidation.valid) {
    e.preventDefault();
    errorMessage.classList.toggle("hidden");
    errorMessage.textContent = phoneValidation.error;
    return;
  }

  const passwordValidation = validateUserPassword(password);
  if (!passwordValidation.valid) {
    e.preventDefault();
    errorMessage.classList.toggle("hidden");
    errorMessage.textContent = passwordValidation.error;
    return;
  }
});
