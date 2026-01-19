import { handleEntityImageElement } from "../UIhelpers.js";

export function hydrateGenreForm(form, data) {
  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input || input.type === "file") return;
    input.value = data[key];
  });

  if (data.image) {
    handleEntityImageElement("set", data.image);
  }
}
