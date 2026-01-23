export async function loadDashboardKPIs_DB() {
  const result = await fetch("../../backend/dashboard/kpis.php");

  return result.json();
}

export async function loadLastFiveOrders_DB() {
  const result = await fetch("../../backend/dashboard/get_recent_orders.php");

  return result.json();
}

export async function getAdminSession() {
  const result = await fetch("../../backend/dashboard/get_admin_session.php");

  return result.json();
}
