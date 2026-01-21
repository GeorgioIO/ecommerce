import { hydrateOrderLinesTable } from "./orderLines.js";

export function hydrateOrderForm(
  form,
  orderMetaData,
  orderAddressData,
  orderLines,
) {
  const mode = form.dataset.mode;
  const status = orderMetaData.status;
  hydrateOrderMetaDataSection(form, orderMetaData);
  hydrateOrderAddressSection(form, orderAddressData);
  hydrateOrderLinesTable(orderLines, mode, status);
}

function hydrateOrderMetaDataSection(form, data) {
  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];

    if (input.type === "date") {
      input.value = new Date(data[key]).toISOString().split("T")[0];
    }
  });
}

function hydrateOrderAddressSection(form, data) {
  const addressSelect = form.querySelector("#existing-address-select");
  if (data.admin_made === 1) {
    addressSelect.value = null;
  } else {
    addressSelect.value = data.address_id;
  }

  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`#${key}`);
    if (!input) return;
    input.value = data[key];
  });
}
