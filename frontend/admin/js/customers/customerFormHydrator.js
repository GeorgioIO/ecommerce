export function hydrateCustomerForm(form, data) {
  const ordersCountText = form.querySelector(".orders-count-form");
  const totalSpentText = form.querySelector(".total-spent-form");

  ordersCountText.textContent = `Orders Count: ${data.total_orders}`;
  totalSpentText.textContent = `Total Spent: $${data.total_spent}`;

  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];
  });
}
