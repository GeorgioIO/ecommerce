import { populateSelectOrderStatus } from "./orderUI.js";

export function populateOrderFormSelect(form) {
  const statutsSelect = form.querySelector("#order_status");

  populateSelectOrderStatus(statutsSelect);
}
