<?php

require_once __DIR__ . '/../../backend/auth/admin_guard.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | BookNest</title>
    <link rel="stylesheet" href="../admin/css/admin_styles.css" />
    <script defer type="module" src="../admin/js/sidebar.js"></script>
    <script defer type="module" src="../admin/js/adminUIController.js"></script>
  </head>
  <body>
    <main>
      <div class="message-log">
        <p class="message-log-text">This is the message log</p>
      </div>
      <div id="confirmation-modal">
        <p class="confirmation-text"></p>
        <div class="confirmation-buttons-container">
          <button id="close-confirmation-modal">Back</button>
          <button id="delete-entity-btn" data-intent="delete">Confirm</button>
        </div>
      </div>
      <div class="form-container">
        <div class="form-header">
          <button id="close-operation-form">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="35"
              height="35"
              class="icon line"
              viewBox="0 0 24 24"
            >
              <path
                d="M21 12H3m3-3-3 3 3 3"
                style="
                  fill: none;
                  stroke: #000;
                  stroke-linecap: round;
                  stroke-linejoin: round;
                  stroke-width: 1.5;
                "
              />
            </svg>
          </button>
          <p class="form-operation-text"></p>
        </div>
        <div class="form-body"></div>
      </div>
      <div class="sidebar">
        <h1><span class="booknest-text">BookNest</span> Dashboard</h1>
        <ul>
          <li>
            <button class="adm-sidebar-button active-sidebar-btn" data-section="dashboard">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                fill="none"
                viewBox="0 0 16 16"
              >
                <path
                  fill="#000"
                  d="M1 6v9h5v-4a2 2 0 1 1 4 0v4h5V6L8 0 1 6Z"
                />
              </svg>
              <p>Dashboard</p>
            </button>
          </li>
          <li>
            <button class="adm-sidebar-button" data-section="order">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                aria-hidden="true"
                viewBox="0 0 14 14"
              >
                <path
                  d="M12.667 7.667H11v2l-.667-.444-.666.444v-2H8A.334.334 0 0 0 7.667 8v4c0 .183.15.333.333.333h4.667c.183 0 .333-.15.333-.333V8a.334.334 0 0 0-.333-.333zm-8-1.334h4.666c.184 0 .334-.15.334-.333V2a.334.334 0 0 0-.334-.333H7.667v2L7 3.223l-.667.444v-2H4.667A.334.334 0 0 0 4.333 2v4c0 .183.15.333.334.333zM6 7.667H4.333v2l-.666-.444L3 9.667v-2H1.333A.334.334 0 0 0 1 8v4c0 .183.15.333.333.333H6c.183 0 .333-.15.333-.333V8A.334.334 0 0 0 6 7.667z"
                />
              </svg>
              <p>Orders</p>
            </button>
          </li>
          <li>
            <button class="adm-sidebar-button" data-section="customer">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                viewBox="0 0 24 24"
              >
                <path
                  fill="#000"
                  fill-rule="evenodd"
                  d="M8 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm8 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm-8 2a7.98 7.98 0 0 1 6 2.708V19H2v-1.292A7.98 7.98 0 0 1 8 15Zm8 4v-2.048l-.5-.567a10.057 10.057 0 0 0-1.25-1.193A8.028 8.028 0 0 1 16 15a7.98 7.98 0 0 1 6 2.708V19h-6Z"
                />
              </svg>
              <p>Customers</p>
            </button>
          </li>
          <li>
            <button class="adm-sidebar-button" data-section="book">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                viewBox="0 0 512 512"
              >
                <path
                  fill="#000"
                  fill-rule="evenodd"
                  d="m256 34.347 192 110.85V366.9L256 477.752 64 366.9V145.198L256 34.347Zm-64.001 206.918.001 150.27 42.667 24.636V265.899l-42.668-24.634ZM106.667 192v150.267l42.666 24.635v-150.27L106.667 192Zm233.324-59.894-125.578 72.836L256 228.952l125.867-72.669-41.876-24.177ZM256 83.614l-125.867 72.669 41.662 24.053L297.374 107.5 256 83.614Z"
                />
              </svg>
              <p>Books</p>
            </button>
          </li>
          <li>
            <button class="adm-sidebar-button" data-section="author">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                xml:space="preserve"
                width="25"
                height="25"
                viewBox="0 0 190.06 190.06"
              >
                <path
                  d="M74.855 43.025c-10.73-14.002-31.93-16.26-47.206-8.84-15.74 7.646-19.837 20.004-22.318 36.07-.938 6.078-2.285 12.806-1.322 18.95.025.162.148.505.327.88.007.106.011.211.019.317a2.768 2.768 0 0 0-.097.02c-1.345.311-1.339 2.391 0 2.705 5.374 1.262 11.005 1.763 16.698 1.952 5.135 8 17.062 12.442 26.559 12.487 7.552.036 15.428-2.715 20.226-8.733a16.874 16.874 0 0 0 1.619-2.48c4.592.026 9.123-.193 13.455-.919.473-.079.822-.326 1.102-.637 1.289.093 2.675-.687 2.715-2.354.415-16.847-1.172-35.58-11.777-49.418zM140.683 68.427c-12.234-.564-27.683 1.199-38.47 7.469-.698.406-.768 1.209-.467 1.808-5.535 12.933 7.087 23.47 22.425 26.943-.466 1.787-.215 3.979-.291 5.595-.209 4.429.03 9.219 1.852 13.322 3.709 8.352 11.908 6.092 19.282 4.73 1.091-.201 1.843-1.381 1.849-2.427.021-4.707.046-9.414.039-14.122-.004-2.491.173-5.111.148-7.698 8.583-2.807 15.386-9.011 17.029-19.646.069-.451.019-.86-.104-1.229.194-.224.352-.498.41-.877 1.755-11.262-16.281-13.526-23.702-13.868z"
                />
                <path
                  d="M161.333 44.139c-7.608-6.377-18.305-12.044-28.522-10.951-13.587 1.454-44.702 16.072-34.179 34.116.109.187.245.335.393.458.067.688.7 1.342 1.523 1.119 13.963-3.786 29.942-11.271 44.73-7.76 4.397 1.044 8.683 3.013 12.773 4.878a76.738 76.738 0 0 1 5.172 2.628c.673.379 1.331.784 1.974 1.214.29.187.501.337.667.463a1.956 1.956 0 0 0 1.877 1.99c2.062.112 2.972-1.106 2.997-2.554.817.1 1.712-.226 2.147-.977 5.033-8.689-5.557-19.598-11.552-24.624zM183.553 130.486c-3.356-5.223-15.951-21.392-24.495-19.159-.874-.67-2.129-.81-3.009.176-2.332 2.615-2.227 6.881-2.574 10.188-.56 5.334-.686 10.795-1.759 16.064-11.903-1.195-23.748 2.08-35.601.652.069-8.096.336-16.262-1.424-24.183.96-1.025.695-3.035-.553-3.806-7.473-4.607-18.241 8.033-22.205 12.755-1.031 1.229-3.695 4.444-5.631 7.689-5.083-8.275-14.536-14.853-23.763-14.402-1.467-1.078-4.24-.385-4.409 2.054-.263 3.776.083 7.613-.471 11.366-.789 5.345-5.969 5.386-10.087 5.3-5.248-.109-10.593-1.166-15.792-.375-.109-4.39-.211-8.777-.375-13.164-.059-1.582.349-4.753-1.262-5.852l-.002-.002c-.041-.027-.068-.069-.112-.095-.048-.028-.093-.027-.141-.048a1.125 1.125 0 0 0-.526-.125c-.028 0-.056.007-.084.008-1.172-2.561-5.591-1.17-7.808-.216-6.328 2.722-12.717 9.518-16.765 14.914-4.222 5.629-5.735 14.696-4 21.452 1.713 6.668 11.312 5.716 16.411 5.56 10.735-.33 21.159-1.408 31.94-1.192 16.462.329 32.908 1.225 49.365 1.727 15.853.482 31.634.683 47.471 1.645 7.742.472 15.485.764 23.24.536 5.145-.15 11.948.197 16.566-2.534.951-.562 1.288-1.381 1.22-2.168 6.921-6.388.853-18.2-3.365-24.765z"
                />
              </svg>
              <p>Authors</p>
            </button>
          </li>
          <li>
            <button class="adm-sidebar-button" data-section="genre">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                viewBox="0 0 20 20"
              >
                <path
                  fill="#000"
                  fill-rule="evenodd"
                  d="M5.02 6.227a1.038 1.038 0 0 1-1.043-1.032c0-.569.467-1.03 1.043-1.03.576 0 1.043.461 1.043 1.03 0 .57-.467 1.032-1.043 1.032m14.369 4.42-3.455-3.414C10.06 1.429 11.435 2.819 9.158.419 8.962.225 8.697 0 8.42 0H2.085C.934 0 0 1.157 0 2.295v6.26c0 .274.11.536.305.73 4.091 4.042 1.145 1.13 10.232 10.111a2.104 2.104 0 0 0 2.95 0l5.902-5.833a2.045 2.045 0 0 0 0-2.915"
                />
              </svg>
              <p>Genres</p>
            </button>
          </li>
          <li>
            <button data-section="logout" class="adm-sidebar-button">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  fill="#0F0F0F"
                  fill-rule="evenodd"
                  d="M20 23h-8a1 1 0 1 1 0-2h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-8a1 1 0 1 1 0-2h8a3 3 0 0 1 3 3v16a3 3 0 0 1-3 3Z"
                  clip-rule="evenodd"
                />
                <path
                  fill="#0F0F0F"
                  fill-rule="evenodd"
                  d="M18.688 10.69a2 2 0 0 1 0 2.62l-4.177 4.82C13.3 19.527 11 18.67 11 16.82V15H5a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h6V7.18c0-1.85 2.299-2.708 3.511-1.31l4.177 4.82Zm-2.079 1.965a1 1 0 0 0 0-1.31L13 7.181V9.5a1.5 1.5 0 0 1-1.5 1.5H5v2h6.5a1.5 1.5 0 0 1 1.5 1.5v2.32l3.61-4.165Z"
                  clip-rule="evenodd"
                />
              </svg>
              <p >Log Out</p>
            </button>
          </li>
        </ul>
      </div>
      <div class="content">
        <div class="table-container">
            
        </div>
      </div>
    </main>
  </body>
</html>
