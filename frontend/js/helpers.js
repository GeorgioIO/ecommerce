import { showMessageLog } from "./messageLog/messageLog.js";

export function validateIDEligibility(id) {
  if (Number.isInteger(parseInt(id)) != true) {
    return {
      valid: false,
      error: "Error in ID : there is a problem with the id",
    };
  }
  return {
    valid: true,
    error: "",
  };
}

export function swapClass(element, classA, classR) {
  element.classList.remove(classR);
  element.classList.add(classA);
}

export function handleImageFormat(file) {
  if (!file) return;

  if (!file.type.startsWith("image")) {
    showMessageLog("error", "Please insert a valid image");
    return;
  }
}

export function handleEntityImageElement(mode = "set", source = "") {
  const imageEmptyText = document.querySelector(".empty-image-text");
  const image = document.querySelector(".entity-image-display");

  if (mode === "reset") {
    image.src = "";
    imageEmptyText.style.display = "flex";
    image.style.display = "none";
    return;
  }

  if (mode === "set") {
    if (!source) return;

    imageEmptyText.style.display = "none";
    image.style.display = "block";

    if (source instanceof File) {
      image.src = URL.createObjectURL(source);
      return;
    }

    if (typeof source === "string") {
      image.src = "../assets/images/" + source;
      return;
    }
  }
}
