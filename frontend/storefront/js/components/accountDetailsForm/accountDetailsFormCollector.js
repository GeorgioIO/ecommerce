export function accountDetailsFormCollector(form) {
  return {
    name: form.querySelector("#name").value ?? null,
    email: form.querySelector("#email").value ?? null,
    phoneNumber: form.querySelector("#phone_number").value ?? null,
    currentPassword: form.querySelector("#password").value ?? null,
    newPassword: form.querySelector("#new_password").value ?? null,
    confirmPassword: form.querySelector("#confirm_password").value ?? null,
  };
}
