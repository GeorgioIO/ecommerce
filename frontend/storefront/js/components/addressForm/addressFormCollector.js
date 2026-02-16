export function collectFormData(form) {
  return {
    firstName: form.querySelector("#first_name").value ?? null,
    lastName: form.querySelector("#last_name").value ?? null,
    email: form.querySelector("#email").value ?? null,
    phoneNumber: form.querySelector("#phone_number").value ?? null,
    state: form.querySelector("#state").value ?? null,
    city: form.querySelector("#city").value ?? null,
    addressLine1: form.querySelector("#address_line1").value ?? null,
    addressLine2: form.querySelector("#address_line2").value ?? null,
    additionalNotes: form.querySelector("#additional_notes").value ?? null,
  };
}
