import { buildCheckoutCard } from "../components/checkoutCard.js";
import {
  activateMessageBox,
  appendMessageBox,
  createMesssageBox,
} from "../components/messageBox.js";
import { buildAddressOptionContainer } from "../core/UIhelpers.js";
import { getCartItems } from "../services/cartServices.js";
import { getCustomerAddresses_DB } from "../services/customerServices.js";
import { getSession } from "../services/sessionServices.js";
import { buildAddressFormSkeleton } from "../components/addressForm/addressFormBuilder.js";
import { hydrateAddressForm } from "../components/addressForm/addressFormHydrator.js";
import { placeOrder } from "../services/ordersServices.js";
import { validateAddressData } from "../components/addressForm/addressFormValidator.js";

const shippingMethods = [
  {
    title: "Standard Shipping",
    price: 2.0,
  },
];

const paymentMethods = [
  {
    title: "Cash on Delivery (COD)",
  },
];

export async function renderCheckoutSection() {
  // TODO : We have two states here
  // ! 1. User has items in his carts
  // ! 2. User cart is empty
  // ! Sub User is not logged in

  // Steps
  // 1. Get data -- DONE
  // 2. Check length -- DONE
  // 2.1 if failed render fail (Not logged in)
  // 2.2 if empty render empty checkout
  // 2.3 if there is data render data

  const { success, status, data, message } = await getCartItems();

  if (!success) {
    activateMessageBox();
    const messageBox = createMesssageBox(message);
    appendMessageBox(messageBox);
    renderErrorCheckout(status);
    return;
  }

  if (data.length === 0) {
    renderEmptyCheckout();
    return;
  }

  // There is data
  if (data.length > 0) {
    renderActiveCheckout(data);
  }
}

async function renderActiveCheckout(data) {
  const checkoutSection = document.querySelector("#checkout");
  const checkoutContainer = document.createElement("div");
  checkoutContainer.classList.add("checkout-container");

  // TODO : The function that is responsible to create and render the checkout full section
  // Two Main Parts :
  // - Order summary : (Function) -- Done
  //    - Order lines Part A
  //    - Subtotal Part B
  //    - Shipping Part B
  //    - Total Part B
  // - Order information : (Function) -- Done
  //    - Email (Get stored in shipping address)
  //    - Shipping Address
  //        - if user already have one :
  //            -  Give him choice to use exsiting one
  //            -  Use new one (tell him will not be saved)
  //    - Shipping tax (2$ static) -- DONE
  //    - Payment (COD static)
  //  - Button to place order (Function)
  // TODO : Render Order Summary
  checkoutSection.innerHTML = "";

  // Order Summary
  const orderSummary = renderOrderSummary(checkoutContainer, data);

  // Order Information
  const orderInformation = await renderOrderInformation();

  checkoutSection.append(orderSummary, orderInformation);
}

async function renderOrderInformation() {
  const orderInformation = document.createElement("div");
  orderInformation.classList.add("order-information-container");

  // Order Address
  const customerAddress = await getCustomerAddresses_DB();
  console.log(customerAddress);
  const orderAddressContainer = renderOrderAddress(customerAddress);

  // Shipping
  const shippingContainer = renderShippingMethods();

  // Payment
  const paymentContainer = renderPaymentMethods();

  const addressID = customerAddress[0]?.address_id ?? null;
  // Complete Order button
  const completeOrderButon = renderCompleteOrderButton(addressID);

  orderInformation.append(
    orderAddressContainer,
    shippingContainer,
    paymentContainer,
    completeOrderButon,
  );

  return orderInformation;
}

async function attachCompleteOrder(addressID) {
  activateMessageBox();

  try {
    const form = document.querySelector("#address-form");
    const defaultRadio =
      document.querySelector("#default-address-radio") ?? null;
    let newAddress = null;

    console.log(defaultRadio);
    if (!defaultRadio || (defaultRadio && defaultRadio.checked !== true)) {
      newAddress = {
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

      addressID = null;

      const dataValidation = validateAddressData(newAddress);

      if (!dataValidation.valid) {
        const messageBox = createMesssageBox(dataValidation.message);
        appendMessageBox(messageBox);
        return;
      }
    }

    const response = await placeOrder(addressID, newAddress);

    const messageBox = createMesssageBox(response.message);
    appendMessageBox(messageBox);
  } catch (error) {
    console.log(error);
    const messageBox = createMesssageBox(
      "Problem placing order please try again",
    );
    appendMessageBox(messageBox);
  }
}

function renderCompleteOrderButton(addressID) {
  const completeOrderContainer = document.createElement("div");
  completeOrderContainer.classList.add("complete-order-container");

  const button = document.createElement("button");
  button.id = "complete-order-button";
  button.textContent = "Complete Order";

  button.onclick = async () => await attachCompleteOrder(addressID);

  completeOrderContainer.append(button);
  return completeOrderContainer;
}

function renderPaymentMethods() {
  const paymentContainer = document.createElement("div");
  paymentContainer.classList.add("payment-container");

  const containerTitle = document.createElement("h4");
  containerTitle.classList.add("container-title");
  containerTitle.textContent = "Payment method";

  const paymentMethodsContainer = document.createElement("div");
  paymentMethodsContainer.classList.add("payment-methods-container");

  paymentMethods.forEach((method) => {
    const methodElement = document.createElement("div");
    methodElement.classList.add("payment-method");

    const methodTitle = document.createElement("p");
    methodTitle.textContent = method.title;

    methodElement.append(methodTitle);
    paymentMethodsContainer.append(methodElement);
  });

  paymentContainer.append(containerTitle, paymentMethodsContainer);

  return paymentContainer;
}

function renderShippingMethods() {
  const shippingContainer = document.createElement("div");
  shippingContainer.classList.add("shipping-container");

  const containerTitle = document.createElement("h4");
  containerTitle.classList.add("container-title");
  containerTitle.textContent = "Shipping method";

  const shippingMethodsContainer = document.createElement("div");
  shippingMethodsContainer.classList.add("shipping-methods-container");

  shippingMethods.forEach((method) => {
    const methodElement = document.createElement("div");
    methodElement.classList.add("shipping-method");

    const methodTitle = document.createElement("p");
    methodTitle.textContent = method.title;

    const methodPrice = document.createElement("p");
    methodPrice.innerHTML = `<strong>$${method.price}</strong>`;

    methodElement.append(methodTitle, methodPrice);

    shippingMethodsContainer.append(methodElement);
  });

  shippingContainer.append(containerTitle, shippingMethodsContainer);

  return shippingContainer;
}

function renderOrderAddress(customerAddress) {
  let hasAddress = customerAddress.length === 0 ? false : true;

  const orderAddressContainer = document.createElement("div");
  orderAddressContainer.classList.add("order-address-container");

  const containerTitle = document.createElement("h4");
  containerTitle.classList.add("container-title");
  containerTitle.textContent = "Delivery";

  orderAddressContainer.append(containerTitle);

  // Address Form
  const addressForm = buildAddressFormSkeleton(customerAddress, "checkout");

  if (hasAddress) {
    const addressID = customerAddress[0].address_id;
    const addressOptionContainer = buildAddressOptionContainer(addressID);
    orderAddressContainer.append(addressOptionContainer);
  }
  if (hasAddress) hydrateAddressForm(addressForm, customerAddress[0]);

  orderAddressContainer.append(addressForm);

  return orderAddressContainer;
}

function renderOrderSummary(container, data) {
  const orderSummary = document.createElement("div");
  orderSummary.classList.add("checkout-order-summary-container");
  let subTotal = 0;
  let total = 0;

  // Order items part
  const checkoutItemsContainer = document.createElement("div");
  checkoutItemsContainer.classList.add("checkout-items-container");

  data.forEach((product) => {
    // For each product create checkout card
    // Add it to checkoutContainer
    const checkoutCard = buildCheckoutCard(product);
    subTotal += parseFloat(product.final_price);
    checkoutItemsContainer.append(checkoutCard);
  });

  total = subTotal + 2.0;

  orderSummary.append(checkoutItemsContainer);

  // Order price part
  const orderPriceContainer = document.createElement("div");
  orderPriceContainer.classList.add("order-price-container");

  // Subtotal + Shippiing
  const subTotalAndShipping = document.createElement("div");
  subTotalAndShipping.classList.add("subtotal-and-shipping-container");

  const subTotalRow = document.createElement("div");
  subTotalRow.classList.add("sub-total-row");

  const subTotalText = document.createElement("p");
  subTotalText.textContent = "Subtotal";

  const subTotalPrice = document.createElement("p");
  subTotalPrice.textContent = `$${subTotal.toFixed(2)}`;

  subTotalRow.append(subTotalText, subTotalPrice);

  const shippingRow = document.createElement("div");
  shippingRow.classList.add("shipping-row");

  const shippingText = document.createElement("p");
  shippingText.textContent = "Shipping";

  const shippingPrice = document.createElement("p");
  shippingPrice.textContent = "$2.00";

  shippingRow.append(shippingText, shippingPrice);

  subTotalAndShipping.append(subTotalRow, shippingRow);

  // Total
  const totalRow = document.createElement("div");
  totalRow.classList.add("total-row");

  const totalText = document.createElement("p");
  totalText.innerHTML = "<strong>Total</strong>";

  const totalPrice = document.createElement("p");
  totalPrice.textContent = `$${total.toFixed(2)}`;

  totalRow.append(totalText, totalPrice);

  orderPriceContainer.append(subTotalAndShipping, totalRow);

  orderSummary.append(orderPriceContainer);

  return orderSummary;
}

function renderEmptyCheckout() {
  const checkoutSection = document.querySelector("#checkout");
  const emptyCheckoutContainer = document.createElement("div");
  emptyCheckoutContainer.classList.add("empty-checkout-container");
  checkoutSection.innerHTML = "";

  const text = document.createElement("p");
  text.textContent = "Currently there is no products in your cart.";

  const button = document.createElement("a");
  button.classList.add("checkout-redirection-button");
  button.href = "../pages/products.php";
  button.textContent = "Browse Products";

  emptyCheckoutContainer.append(text, button);
  checkoutSection.append(emptyCheckoutContainer);
}

function renderErrorCheckout(status) {
  const checkoutSection = document.querySelector("#checkout");
  const errorContainer = document.createElement("div");
  errorContainer.classList.add("error-checkout-container");

  checkoutSection.innerHTML = "";
  let errorMessage = "Fail in loading cart , try again later...";
  let hrefText = "../pages/home.php";
  let buttonText = "Home";

  if (status === 401) {
    errorMessage =
      "Please make sure to log in before using cart or checkouts services";
    hrefText = "../pages/my-account.php";
    buttonText = "Log in";
  }

  const text = document.createElement("p");
  text.textContent = errorMessage;

  const button = document.createElement("a");
  button.classList.add("checkout-redirection-button");
  button.href = hrefText;
  button.textContent = buttonText;

  errorContainer.append(text, button);

  checkoutSection.append(errorContainer);
}
