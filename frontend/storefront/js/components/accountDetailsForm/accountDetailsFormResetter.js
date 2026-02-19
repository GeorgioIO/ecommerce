export function resetPasswordFields(form) {
  form.querySelector("#password").value = "";
  form.querySelector("#new_password").value = "";
  form.querySelector("#confirm_password").value = "";
}
