import { getFormats_DB } from "./formatsServices.js";

export async function populateSelectFormats(selectElement) {
  const formats = await getFormats_DB();

  // Default option
  const defaultOptionElement = document.createElement("option");
  defaultOptionElement.value = "";
  defaultOptionElement.textContent = "Select Format";
  selectElement.append(defaultOptionElement);

  formats.data.forEach((format) => {
    let optionElement = document.createElement("option");
    optionElement.value = format.id;
    optionElement.textContent = format.name;
    selectElement.append(optionElement);
  });
}
