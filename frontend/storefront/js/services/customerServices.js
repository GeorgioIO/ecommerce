export async function get_customer_addresses_DB(customer_id) {
  const result = await fetch(
    "../../../backend/customers/get_customer_addresses.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        id: customer_id,
      }),
    },
  );
  return result.json();
  // console.log(result.text());
}

export async function deleteCustomerAddress_DB() {
  const result = await fetch(
    "../../../backend/customers/delete_customer_address.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
    },
  );
  return result.json();
  // console.log(result.text());
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
  formData.append("additional_notes", data.additional_notes);

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
