export function hydrateAddressForm(form, data) {
  if (!data) return;
  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;

    input.value = data[key];
  });
}
