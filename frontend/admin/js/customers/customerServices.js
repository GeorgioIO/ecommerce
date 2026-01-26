export async function fetch_customers_DB(pagination = null) {
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

// function responsible to get data about single customer
export async function get_customer_data_DB(customer_id) {
  const result = await fetch("../../backend/customers/get_customer.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: customer_id,
    }),
  });

  return result.json();
  // console.log(result.text());
}

// Function responsible to get addresses of a given customer if

export async function get_customer_addresses_DB(customer_id) {
  const result = await fetch(
    "../../backend/customers/get_customer_addresses.php",
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
}
