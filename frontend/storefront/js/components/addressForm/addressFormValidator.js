import { isValidEmail, isValidPhone } from "../../core/helpers.js";

export function validateAddressData(data) {
  console.log(data);
  // first name
  const first_name = data.firstName.trim();

  if (!first_name || first_name === "") {
    return {
      valid: false,
      error: "Error in first name : cannot be empty",
    };
  }

  if (first_name.length > 255) {
    return {
      valid: false,
      error: "Error in first name : cannot succedd 255 characters",
    };
  }

  // last name
  const last_name = data.lastName.trim();

  if (!last_name || last_name === "") {
    return {
      valid: false,
      error: "Error in last name : cannot be empty",
    };
  }

  if (last_name.length > 255) {
    return {
      valid: false,
      error: "Error in last name : cannot succedd 255 characters",
    };
  }

  // email
  const email = data.email.trim();
  if (!email || email === "") {
    return {
      valid: false,
      error: "Error in email : cannot be empty",
    };
  }

  if (!isValidEmail(email)) {
    return {
      valid: false,
      error: "Error in email : Invalid Email",
    };
  }

  if (email.length > 55) {
    return {
      valid: false,
      error: "Error in email : cannot succeed 55 characters",
    };
  }

  // phone number
  const phoneNumber = data.phoneNumber.trim();
  if (!phoneNumber || phoneNumber === "") {
    return {
      valid: false,
      error: "Error in phone number : cannot be empty",
    };
  }

  if (!isValidPhone(phoneNumber)) {
    return {
      valid: false,
      error: "Error in phone number : Invalid Phone Number",
    };
  }

  // state
  const state = data.state.trim();

  if (!state || state === "") {
    return {
      valid: false,
      error: "Error in state : cannot be empty",
    };
  }

  if (state.length > 55) {
    return {
      valid: false,
      error: "Error in state : cannot succedd 55 characters",
    };
  }

  // city
  const city = data.city.trim();

  if (!city || city === "") {
    return {
      valid: false,
      error: "Error in city : cannot be empty",
    };
  }

  if (city.length > 55) {
    return {
      valid: false,
      error: "Error in city : cannot succedd 55 characters",
    };
  }

  // Address line 1
  const addressLine1 = data.addressLine1.trim();

  if (!addressLine1 || addressLine1 === "") {
    return {
      valid: false,
      error: "Error in Address Line 1 : cannot be empty",
    };
  }

  if (addressLine1.length > 255) {
    return {
      valid: false,
      error: "Error in Address Line 1 : cannot succedd 255 characters",
    };
  }

  // Address line 2
  const addressLine2 = data.addressLine2?.trim();

  if (!addressLine2 && addressLine2?.length > 255) {
    return {
      valid: false,
      error: "Error in Address Line 2 : cannot succedd 255 characters",
    };
  }

  // Additional Notes
  const additionalNotes = data.additionalNotes?.trim();

  if (!additionalNotes && additionalNotes?.length > 255) {
    return {
      valid: false,
      error: "Error in Additional Notes : cannot succedd 255 characters",
    };
  }

  return { valid: true };
}
