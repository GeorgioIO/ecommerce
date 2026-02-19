import { isValidEmail, isValidPhone } from "../../core/helpers.js";
import { validateUserPassword } from "../../core/validators/registrationValidators.js";

export function validateAccountDetailsData(data) {
  /*
        - name
        - email 
        - phone number
        - passwords
    */

  // username
  const name = data.name.trim();

  if (name.length > 255) {
    return {
      valid: false,
      error: "Error in name : cannot succeed 255 characters",
    };
  }

  // email
  const email = data.email.trim();

  if (!isValidEmail(email)) {
    return {
      valid: false,
      error: "Error in email : invalid email",
    };
  }

  if (email.length > 55) {
    return {
      valid: false,
      error: "Error in email : cannot succeed 55 characters",
    };
  }

  // Phone number
  const phone = data.phoneNumber.trim();

  if (!isValidPhone(phone)) {
    return {
      valid: false,
      error: "Error in phone number : Invalid Phone Number",
    };
  }

  // Password : current pass , new pass , confirm pass
  const currentPass = data.currentPassword.trim();
  const newPass = data.newPassword.trim();
  const confirmPass = data.confirmPassword.trim();

  if (newPass && confirmPass && newPass !== confirmPass) {
    return {
      valid: false,
      error: "Password doesnt match",
    };
  }

  const currentPassValidation = validateUserPassword(currentPass);
  if (currentPass && !currentPassValidation.valid) {
    return {
      valid: false,
      error: currentPassValidation.error,
    };
  }

  const newPassValidation = validateUserPassword(newPass);
  if (newPass && !newPassValidation.valid) {
    return {
      valid: false,
      error: newPassValidation.error,
    };
  }

  const confirmPassValidation = validateUserPassword(confirmPass);
  if (confirmPass && !confirmPassValidation.valid) {
    return {
      valid: false,
      error: confirmPassValidation.error,
    };
  }

  return { valid: true };
}
