const twoTicksIcon = `

<svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24">
  <path fill="#3b5ae6" fill-rule="evenodd" d="m18 7-1.41-1.41-6.34 6.34 1.41 1.41L18 7Zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41ZM.41 13.41 6 19l1.41-1.41L1.83 12 .41 13.41Z"/>
</svg>

`;

export function buildNotificationContainer() {
  const notificationContainer = document.createElement("div");
  notificationContainer.classList.add("notifications-container");

  // Create Header Part
  const notificationHeader = document.createElement("div");
  notificationHeader.classList.add("notification-container-header");

  const notificationHeaderIntro = document.createElement("div");
  notificationHeaderIntro.classList.add("notification-header-intro");

  const notificationContainerTitle = document.createElement("h3");
  notificationContainerTitle.classList.add("notification-section-title");
  notificationContainerTitle.textContent = "Notifications";

  const notificationMarkAllAsReadButton = document.createElement("button");
  notificationMarkAllAsReadButton.id = "mark-all-as-read-button";

  notificationMarkAllAsReadButton.innerHTML = `
    ${twoTicksIcon} <p> Mark as read </p>
  `;

  notificationHeaderIntro.append(
    notificationContainerTitle,
    notificationMarkAllAsReadButton,
  );

  const closeNotificationSectionButton = document.createElement("button");
  closeNotificationSectionButton.id = "close-notification-section-button";
  const caret = document.createElement("div");
  caret.classList.add("close-caret");
  closeNotificationSectionButton.append(caret);

  notificationHeader.append(
    notificationHeaderIntro,
    closeNotificationSectionButton,
  );

  // Create Notification Part
  const notificationUL = document.createElement("ul");
  notificationUL.classList.add("notifications-ul");

  notificationContainer.append(notificationHeader, notificationUL);

  return notificationContainer;
}
