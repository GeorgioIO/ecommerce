export async function updateAccount_DB(data) {
  const formData = new FormData();
  formData.append("name", data.name);
  formData.append("email", data.email);
  formData.append("phone_number", data.phoneNumber);
  formData.append("current_password", data.currentPassword);
  formData.append("new_password", data.newPassword);
  formData.append("confirm_password", data.confirmPassword);

  const result = await fetch("../../../backend/customers/update_customer.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
}

export async function getCustomer_DB() {
  const result = await fetch("../../../backend/customers/get_customer.php");

  return result.json();
}

export async function getCustomerAddress_DB(customerID) {
  const result = await fetch(
    `../../../backend/customers/get_customer_address.php?id=${customerID}`,
  );

  // console.log(result.text());
  return result.json();
}

export async function deleteCustomerAddress_DB() {
  const result = await fetch(
    "../../../backend/customers/delete_customer_address.php",
    {
      method: "DELETE",
    },
  );
  return result.json();
}

export async function saveCustomerAddress_DB(data) {
  const formData = new FormData();
  formData.append("first_name", data.firstName);
  formData.append("last_name", data.lastName);
  formData.append("email", data.email);
  formData.append("phone_number", data.phoneNumber);
  formData.append("state", data.state);
  formData.append("city", data.city);
  formData.append("address_line1", data.addressLine1);
  formData.append("address_line2", data.addressLine2);
  formData.append("additional_notes", data.additionalNotes);

  const result = await fetch(
    "../../../backend/customers/save_customer_address.php",
    {
      method: "POST",
      body: formData,
    },
  );

  return result.json();
  // console.log(result.text());
}
