export function hydrateAuthorForm(form, data) {
  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];
  });
}
