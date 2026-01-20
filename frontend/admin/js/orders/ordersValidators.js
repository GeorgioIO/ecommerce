import { isValidEmail, isValidPhone } from "../helpers.js";

function validateOrderMetaData(data, mode) {
  if (mode === "add") {
    // customer id
    const customerID = parseInt(data.name);

    // Check if not a number
    if (Number.isNaN(customerID)) {
      return {
        valid: false,
        error: "Error in customer field : Invalid ID",
      };
    }

    // Check if a positive digit
    if (
      typeof customerID === "number" &&
      Number.isInteger(customerID) &&
      customerID < 0
    ) {
      return {
        valid: false,
        error:
          "Error in customer field : Customer ID must be a positive Integer",
      };
    }

    // date added
    const dateAdded = data.dateAdded;
    const todayDate = new Date().toISOString().split("T")[0];

    if (!dateAdded) {
      return {
        valid: false,
        error: "Error in date : date cannot be empty",
      };
    }

    if (dateAdded !== todayDate) {
      return {
        valid: false,
        error: "Error in date : Invalid Date",
      };
    }
  }

  // status
  const status = data.status.trim() || "Pending";

  if (!status || status === "") {
    return {
      valid: false,
      error: "Error in status : Cannot be empty",
    };
  }

  if (
    status !== "Pending" &&
    status !== "Processing" &&
    status !== "Shipped" &&
    status !== "Delivered" &&
    status !== "Cancelled" &&
    status !== "Refunded"
  ) {
    return {
      valid: false,
      error: "Error in status : Invalid value",
    };
  }

  // total price
  const totalPrice = parseFloat(data.totalOrderPrice);

  if (Number.isNaN(totalPrice) || totalPrice < 0) {
    return {
      valid: false,
      error: "Error in price : Price is required to be bigger or equal to 0",
    };
  }

  return { valid: true };
}

function validateOrderAddress(data, mode) {
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

function validateOrderLines(data, totalPrice) {
  let orderTotalPrice = 0;

  if (!Array.isArray(data) || data.length === 0) {
    return {
      valid: false,
      error: "Error in Order Lines : Order must contain at least one product",
    };
  }

  //   const usedBookIDS = new Set();

  for (let i = 0; i < data.length; i++) {
    let line = data[i];

    // book id
    let bookId = parseInt(line.bookId);
    if (Number.isNaN(bookId) || bookId <= 0) {
      return {
        valid: false,
        error: `Error in ID : Invalid product in order line ${i + 1}`,
      };
    }

    // quantity
    let quantity = parseInt(line.quantity);
    if (!Number.isInteger(quantity) || quantity < 1) {
      return {
        valid: false,
        error: `Error in Quantity : Invalid quantity in order line ${i + 1}`,
      };
    }

    // single and total price
    let unitPrice = parseFloat(line.unitPrice).toFixed(2);
    let lineTotalPrice = parseFloat(line.totalLinePrice).toFixed(2);

    if (Number.isNaN(unitPrice) || unitPrice < 0) {
      return {
        valid: false,
        error: `Error in Single Price : Invalid price in order line ${i + 1}`,
      };
    }

    let expectedTotal = parseFloat((unitPrice * quantity).toFixed(2));

    if (Number.isNaN(totalPrice) || lineTotalPrice != expectedTotal) {
      return {
        valid: false,
        error: `Error in total price : Invalid total price in order line ${
          i + 1
        }`,
      };
    }

    orderTotalPrice += expectedTotal;
  }

  if (orderTotalPrice !== totalPrice) {
    return {
      valid: false,
      error: `Error in Total Price : Total price doesnt match the order lines total`,
    };
  }

  return { valid: true };
}

export function validateOrderData(data, mode) {
  const orderMetaData = data.orderMetaData;
  const orderAddressDetails = data.orderAddressDetails;
  const orderLines = data.orderLines;

  // ==== Validate order meta data ====
  /*  
  - customer name (id)
  - status
  - total price
  - date added
  */
  const orderMetaResult = validateOrderMetaData(orderMetaData, mode);
  if (!orderMetaResult.valid) {
    return orderMetaResult;
  }

  const orderAddressDetailsResult = validateOrderAddress(
    orderAddressDetails,
    mode,
  );
  if (!orderAddressDetailsResult.valid) {
    return orderAddressDetails;
  }

  const orderLinesResult = validateOrderLines(
    orderLines,
    parseFloat(orderMetaData.totalPrice),
  );
  if (!orderLinesResult) {
    return orderLinesResult;
  }
  // ==== Validate order address details ====
  /*  
  - first_name
  - last_name
  - email
  - phone_number
  - state
  - city
  - address_line1
  - address_line2
  - additional_notes
  */

  // ==== Validate Order Lines ====

  return { valid: true };
}
