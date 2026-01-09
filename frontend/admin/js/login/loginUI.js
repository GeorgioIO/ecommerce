import { validateLoginData } from "./loginValidators.js";
import { showLoginMessageLog } from "./loginMessageLog.js";
import { checkAdminLogin_DB } from "./loginServices.js";

document.addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const submitButton = e.submitter;

  if (submitButton) {
    // Collect data as an object
    const data = loginDataCollector(form);

    // Validate data JS wise
    const validationResult = validateLoginData(data);
    if (!validationResult.valid) {
      showLoginMessageLog("error", validationResult.error);
      return;
    }

    const checkAdminResult = await checkAdminLogin_DB(data);

    if (!checkAdminResult.success) {
      showLoginMessageLog("error", checkAdminResult.message);
      return;
    } else {
      console.log("hi");
      showLoginMessageLog("success", checkAdminResult.message);

      setTimeout(() => {
        window.location.href = "/ecommerce/frontend/admin/admin_dashboard.php";
      }, 500);
    }
  }
});

function loginDataCollector(form) {
  return {
    email: form.querySelector("#email").value,
    password: form.querySelector("#password").value,
  };
}
