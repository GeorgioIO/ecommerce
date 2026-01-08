export function hydrateCustomerForm(form, data) {
  console.log(data);
  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];
  });
}
