import { isValidEmail, isValidPhone, isValidPassword } from "../helpers.js";

export function validateUsername(name) {
  if (!name || name === "") {
    return {
      valid: false,
      error: "Username is required",
    };
  }

  if (name.length > 255) {
    return {
      valid: false,
      error: "Username cannot succeed 255 characters",
    };
  }

  return { valid: true };
}

export function validateUseremail(email) {
  if (!email) {
    return {
      valid: false,
      error: "Email is required",
    };
  }

  if (email.length > 255) {
    return {
      valid: false,
      error: "Email cannot succeed 255 characters",
    };
  }

  if (!isValidEmail(email)) {
    return {
      valid: false,
      error: "Invalid email",
    };
  }

  return { valid: true };
}

export function validateUserPhoneNumber(phone) {
  if (!phone) return { valid: true };

  if (phone.length > 25) {
    return {
      valid: false,
      error: "Phone number cannot succeed 25 characters",
    };
  }

  if (!isValidPhone(phone)) {
    return {
      valid: false,
      error: "Invalid Phone number",
    };
  }

  return { valid: true };
}

export function validateUserPassword(password) {
  const commonPassword = ["password", "123456", "qwerty", "admin"];

  if (!password) {
    return {
      valid: false,
      error: "Password is required",
    };
  }

  if (password.length < 8) {
    return {
      valid: false,
      error: "Password must atleast be 8 characters",
    };
  }

  if (commonPassword.includes(password.toLowerCase())) {
    return {
      valid: false,
      error: "Password is very common",
    };
  }

  if (!isValidPassword(password)) {
    return {
      valid: false,
      error:
        "Password must atleast have 1 Uppercase , 1 Lowercase , 1 number , 1 special character",
    };
  }

  return { valid: true };
}
