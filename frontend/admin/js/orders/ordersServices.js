export async function get_order_data_DB(order_id) {
  const result = await fetch("../../backend/orders/fetch_single_order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      id: order_id,
    }),
  });

  return result.json();
}

export async function fetchOrderAddress_DB(orderID) {
  const result = await fetch("../../backend/orders/fetch_order_address.php", {
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

export async function fetchOrderLines_DB(orderID) {
  const result = await fetch("../../backend/orders/fetch_order_lines.php", {
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

export async function fetchOrders_DB() {
  const result = await fetch("../../backend/orders/load_orders.php");

  return result.json();
}

export async function fetchOrdersCount_DB() {
  const result = await fetch("../../backend/orders/fetch_order_count.php");

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

  console.log(orderData);
  const result = await fetch("../../backend/orders/update_order.php", {
    method: "POST",
    body: formData,
  });

  return result.json();
  // console.log(result.text());
}
