export async function fetchOrders_DB() {
  const result = await fetch("../../backend/orders/load_orders.php");

  return result.json();
}

export async function fetchOrdersStatus_DB() {}
