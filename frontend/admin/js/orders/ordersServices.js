export async function getOrder_DB(orderID) {
  const result = await fetch(
    `../../backend/orders/get_order.php?id=${orderID}`,
  );

  // console.log(result.text());
  return result.json();
}

export async function getOrderAddress_DB(orderID) {
  const result = await fetch(
    `../../backend/orders/get_order_address.php?id=${orderID}`,
  );

  return result.json();
}

export async function getOrderLines_DB(orderID) {
  const result = await fetch(
    `../../backend/orders/get_order_lines.php?id=${orderID}`,
  );

  return result.json();
}

export async function getOrders_DB(pagination) {
  const params = new URLSearchParams({
    page: pagination.page,
    perPage: pagination.perPage,
  });

  const result = await fetch(
    `../../backend/orders/get_orders.php?${params.toString()}`,
  );

  // console.log(result.text());
  return result.json();
}

export async function getOrdersCount_DB() {
  const result = await fetch("../../backend/orders/get_orders_count.php");

  return result.json();
}

export async function addOrder_DB(orderData) {
  const formData = new FormData();
  // Order Meta Data
  formData.append("user_id", orderData.orderMetaData.name);
  formData.append("status", orderData.orderMetaData.status);
  formData.append("total_order_price", orderData.orderMetaData.totalOrderPrice);
  formData.append("date_added", orderData.orderMetaData.dateAdded);

  // Order Address Details
  formData.append(
    "existing_address_id",
    orderData.orderAddressDetails.existingAddress,
  );
  formData.append("first_name", orderData.orderAddressDetails.firstName);
  formData.append("last_name", orderData.orderAddressDetails.lastName);
  formData.append("email", orderData.orderAddressDetails.email);
  formData.append("phone_number", orderData.orderAddressDetails.phoneNumber);
  formData.append("state", orderData.orderAddressDetails.state);
  formData.append("city", orderData.orderAddressDetails.city);
  formData.append("address_line1", orderData.orderAddressDetails.addressLine1);
  formData.append("address_line2", orderData.orderAddressDetails.addressLine2);
  formData.append(
    "additional_notes",
    orderData.orderAddressDetails.additional_notes,
  );

  // Order Lines
  formData.append("order_lines", JSON.stringify(orderData.orderLines));

  const result = await fetch("../../backend/orders/add_order.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
  // console.log(result.text());
}

export async function updateOrder_DB(orderData) {
  const formData = new FormData();
  // Order Meta Data
  formData.append("id", orderData.orderMetaData.id);
  formData.append("user_id", orderData.orderMetaData.name);
  formData.append("status", orderData.orderMetaData.status);
  formData.append("total_order_price", orderData.orderMetaData.totalOrderPrice);
  formData.append("date_added", orderData.orderMetaData.dateAdded);

  // Order Address Details
  formData.append(
    "existing_address_id",
    orderData.orderAddressDetails.existingAddress,
  );
  formData.append("first_name", orderData.orderAddressDetails.firstName);
  formData.append("last_name", orderData.orderAddressDetails.lastName);
  formData.append("email", orderData.orderAddressDetails.email);
  formData.append("phone_number", orderData.orderAddressDetails.phoneNumber);
  formData.append("state", orderData.orderAddressDetails.state);
  formData.append("city", orderData.orderAddressDetails.city);
  formData.append("address_line1", orderData.orderAddressDetails.addressLine1);
  formData.append("address_line2", orderData.orderAddressDetails.addressLine2);
  formData.append(
    "additional_notes",
    orderData.orderAddressDetails.additional_notes,
  );

  // Order Lines
  formData.append("order_lines", JSON.stringify(orderData.orderLines));

  const result = await fetch("../../backend/orders/update_order.php", {
    method: "POST",
    body: formData,
  });

  // console.log(result.text());
  return result.json();
}
