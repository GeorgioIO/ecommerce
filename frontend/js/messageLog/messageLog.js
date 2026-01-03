import { swapClass } from "../helpers.js";

const messageLog = document.querySelector(".message-log");
const messageLogText = document.querySelector(".message-log-text");

export function showMessageLog(state, message) {
  if (state === "error") {
    swapClass(messageLog, "error-log-state", "success-log-state");
    messageLogText.textContent = message;
    swapClass(messageLog, "slide-down-log", "slide-up-log");
  } else if (state === "success") {
    swapClass(messageLog, "success-log-state", "error-log-state");
    messageLogText.textContent = message;
    swapClass(messageLog, "slide-down-log", "slide-up-log");
  }

  setTimeout(() => hideMessageLog(), 2500);
}

function hideMessageLog() {
  swapClass(messageLog, "slide-up-log", "slide-down-log");
}
