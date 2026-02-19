export function hydrateAccountDetailsForm(form, data) {
  console.log(data);
  if (!data) return;
  Object.keys(data).forEach((key) => {
    if (key !== "name" && key !== "email" && key !== "phone_number") return;
    const input = form.querySelector(`#${key}`);
    if (!input) return;

    input.value = data[key];
  });
}
