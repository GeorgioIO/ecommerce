export async function loadDashboardKPIs_DB() {
  const result = await fetch("../../backend/dashboard/kpis.php");

  return result.json();
}

export async function loadLastFiveOrders_DB() {
  const result = await fetch("../../backend/dashboard/get_recent_orders.php");

  return result.json();
}

export async function loadValuableCustomers_DB() {
  const result = await fetch(
    "../../backend/dashboard/top_valuable_customer.php",
  );

  return result.json();

  // console.log(result.text());
}

export async function loadMostSellingGenres_DB() {
  const result = await fetch("../../backend/dashboard/most_selling_genres.php");

  return result.json();

  // console.log(result.text());
}

export async function getAdminSession() {
  const result = await fetch("../../backend/dashboard/get_admin_session.php");

  return result.json();
}
