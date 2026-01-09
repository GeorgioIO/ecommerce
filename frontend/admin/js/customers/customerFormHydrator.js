const downIcon = `
<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="none" viewBox="0 0 24 24">
  <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m7 10 5 5 5-5"/>
</svg>
`;

export function hydrateCustomerForm(form, data, addresses) {
  const ordersCountText = form.querySelector(".orders-count-form");
  const totalSpentText = form.querySelector(".total-spent-form");
  const addressesList = form.querySelector(".addresses-list");

  ordersCountText.textContent = `Orders Count: ${data.total_orders}`;
  totalSpentText.textContent = `Total Spent: $${data.total_spent}`;

  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];
  });

  var addresses_counter = 0;

  if (addresses.length === 0) {
    const emptyAddressesText = document.createElement("p");
    emptyAddressesText.classList.add("empty-addresses-text");
    emptyAddressesText.textContent = "No Current Addresses";
    addressesList.append(emptyAddressesText);
  } else {
    addresses.forEach((address) => {
      const addressLi = document.createElement("li");
      addressLi.classList.add("address-item");
      addressLi.dataset.state = "closed";

      const addressToggle = document.createElement("button");
      addressToggle.type = "button";
      addressToggle.classList.add("address-toggle");
      addressToggle.textContent = `Address ${addresses_counter + 1}`;

      if (address.is_default === 1) {
        const defaultSpan = document.createElement("span");
        defaultSpan.classList.add("address-badge");
        defaultSpan.textContent = "Default";
        addressToggle.append(defaultSpan);
      }

      addressToggle.innerHTML += downIcon;

      const addressDetails = document.createElement("div");
      addressDetails.classList.add("address-details");

      Object.keys(address).forEach((detail) => {
        if (detail !== "is_default") {
          const addressDetail = document.createElement("p");
          addressDetail.innerHTML = `
          <strong>${detail}:</strong> ${
            address[detail] === null ? "Not Defined" : address[detail]
          }`;
          addressDetails.append(addressDetail);
        }
      });

      addressLi.append(addressToggle, addressDetails);
      addressesList.append(addressLi);
      addresses_counter++;
    });
  }
}
