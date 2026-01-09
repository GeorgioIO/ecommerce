import { swapClass } from "../helpers.js";

const loginMessageLog = document.querySelector(".login-message-log");

export function showLoginMessageLog(state, message) {
  if (state === "error") {
    swapClass(
      loginMessageLog,
      "login-error-message-state",
      "login-success-message-state"
    );
    loginMessageLog.textContent = message;
    swapClass(
      loginMessageLog,
      "login-message-active",
      "login-message-inactive"
    );
  } else if (state === "success") {
    swapClass(
      loginMessageLog,
      "login-success-message-state",
      "login-error-message-state"
    );
    loginMessageLog.textContent = message;
    swapClass(
      loginMessageLog,
      "login-message-active",
      "login-message-inactive"
    );
  }

  setTimeout(() => hideLoginMessageLog(), 2500);
}

function hideLoginMessageLog() {
  swapClass(loginMessageLog, "login-message-inactive", "login-message-active");
}
