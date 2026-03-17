export async function getCustomers_DB(pagination = null) {
  let params = "";
  if (pagination) {
    params = new URLSearchParams({
      page: pagination.page,
      perPage: pagination.perPage,
    });
  }

  const result = await fetch(
    `../../backend/customers/get_customers.php?${params.toString()}`,
  );

  return result.json();
}

export async function getCustomer_DB(customerID) {
  const result = await fetch(
    `../../backend/customers/get_customer.php?id=${customerID}`,
  );

  return result.json();
  // console.log(result.text());
}

export async function getCustomerAddress_DB(customerID = null) {
  const result = await fetch(
    `../../backend/customers/get_customer_address.php?id=${customerID}`,
  );
  return result.json();
  // console.log(result.text());
}
