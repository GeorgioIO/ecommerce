import { showMessageLog } from "./messageLog/messageLog.js";

export function normalizeOrderLineData(raw) {
  // Coming from search
  if (raw.id) {
    return {
      bookId: raw.id,
      title: raw.title,
      unitPrice: parseFloat(raw.price),
      quantity: 1,
    };
  }

  // Edit mode
  if (raw.book_id) {
    return {
      bookId: raw.book_id,
      title: raw.title,
      unitPrice: parseFloat(raw.price),
      quantity: parseInt(raw.quantity),
    };
  }
}

export function validateIDEligibility(id) {
  if (Number.isInteger(parseInt(id)) != true) {
    return {
      valid: false,
      error: "Error in ID : there is a problem with the id",
    };
  }
  return {
    valid: true,
    error: "",
  };
}

export function handleImageFormat(file) {
  if (!file) return;

  if (!file.type.startsWith("image")) {
    showMessageLog("error", "Please insert a valid image");
    return;
  }
}

export function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

export function isValidPhone(phone) {
  return /^[+]?[\d\s()-]{7,20}$/.test(phone.trim());
}
