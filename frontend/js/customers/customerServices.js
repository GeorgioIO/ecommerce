export async function fetch_customers_DB() {
  const result = await fetch("../backend/customers/load_customers.php");
  return result.json();
}
