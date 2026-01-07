export async function fetch_customers_DB() {
  const result = await fetch("../backend/customers/load_customers.php");
  return result.json();
}

// function responsible to get data about single customer
export async function get_customer_data_DB(customer_id) {
  const res = await fetch("../backend/customers/fetch_single_customer.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: customer_id,
    }),
  });

  return res.json();
}
