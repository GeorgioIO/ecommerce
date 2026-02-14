export async function fetchOrders_DB(pagination) {
  const params = new URLSearchParams({
    page: pagination.page,
    perPage: pagination.perPage,
  });

  const result = await fetch(
    `../../../backend/orders/get_orders.php?${params.toString()}`,
  );

  return result.json();
}

export async function fetchOrderLines_DB(orderID) {
  const result = await fetch("../../../backend/orders/get_order_lines.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: orderID,
    }),
  });

  return result.json();
}
