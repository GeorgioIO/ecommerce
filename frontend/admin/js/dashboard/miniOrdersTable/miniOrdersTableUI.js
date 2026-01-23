import { miniOrdersTableConfigs } from "./miniOrdersTableConfigs.js";

export function renderMiniOrdersTableHeader() {
  const headers = miniOrdersTableConfigs;

  return `
    <div class="mini-flex-table-header">
        ${headers.columns
          .map(
            (columnHeader) =>
              `
                <div>
                    <p> ${columnHeader.headerTitle} </p>
                </div>
            `,
          )
          .join("")}
    </div>  
  `;
}

export function renderMiniOrdersTableRow(item) {
  return `
    <div class="mini-flex-table-row">
        <div>
            <p> ${item.order_code} </p>
        </div>
        <div>
            <p> ${item.customer_name} </p>
        </div>
        <div>
            <p> $${item.total_price} </p>
        </div>
        <div>
            <p> ${item.status} </p>
        </div>   
        <div>
            <p> ${item.time_ago} </p>
        </div>
    </div>
  `;
}
