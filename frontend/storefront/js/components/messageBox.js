import { swapClass } from "../../../admin/js/UIhelpers.js";

export function createMesssageBox(message) {
  const messageBox = document.createElement("div");
  messageBox.classList.add("message-box");

  const messageBoxText = document.createElement("p");
  messageBoxText.textContent = message;

  const closeMessageBoxButton = document.createElement("button");
  closeMessageBoxButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" fill="none" viewBox="-0.5 0 25 25">
            <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/>
        </svg>
    `;

  messageBox.append(messageBoxText, closeMessageBoxButton);

  closeMessageBoxButton.onclick = () => {
    messageBox.remove();
  };

  return messageBox;
}

export function appendMessageBox(messageBox) {
  const messageBoxContainer = document.querySelector(".message-box-container");

  messageBoxContainer.prepend(messageBox);

  showMessageBox(messageBox);

  hideMessageBox(messageBox);
}

function showMessageBox(messageBox) {
  swapClass(messageBox, "slide-in-left", "slide-out-left");
}

function hideMessageBox(messageBox) {
  setTimeout(() => {
    messageBox.remove();
  }, 2000);
}

export function activateMessageBox() {
  const messageBoxContainer = document.querySelector(".message-box-container");

  swapClass(messageBoxContainer, "active", "hidden");
}

export function deactivateMessageBox() {
  const messageBoxContainer = document.querySelector(".message-box-container");

  const messageBoxes = messageBoxContainer.querySelectorAll(".message-box");

  if (messageBoxes) {
    messageBoxes.forEach((box) => box.remove());
  }

  swapClass(messageBoxContainer, "active", "hidden");
}
