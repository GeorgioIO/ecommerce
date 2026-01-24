export function populateNotification(data) {
  const notificationUL = document.querySelector(".notifications-ul");
  data.forEach((notification) => {
    const notificationItem = document.createElement("li");
    notificationItem.classList.add("notification-item");
    notificationItem.dataset.notificationid = notification.id;
    const notificationReadIndicator = document.createElement("div");
    notificationReadIndicator.classList.add("notification-read-indicator");
    notificationReadIndicator.classList.add(
      notification.is_read === 0 ? "unread-notifcation" : "read-notification",
    );
    const notificationInfoContainer = document.createElement("div");
    notificationInfoContainer.classList.add("notification-info-container");

    const notificationTitle = document.createElement("h4");
    notificationTitle.classList.add("notification-title");
    notificationTitle.textContent = notification.title;

    const notificationMessage = document.createElement("p");
    notificationMessage.classList.add("notification-message");

    notificationMessage.textContent = notification.message;

    const date = new Date(notification.created_at.replace(" ", "T"));
    const datePart = new Intl.DateTimeFormat("en-US", {
      month: "short",
      day: "2-digit",
      year: "numeric",
    }).format(date);

    const timePart = new Intl.DateTimeFormat("en-US", {
      hour: "2-digit",
      minute: "2-digit",
      hour12: true,
    }).format(date);

    const notificationDate = document.createElement("p");
    notificationDate.classList.add("notification-date");
    notificationDate.textContent = `${datePart} at ${timePart}`;

    notificationInfoContainer.append(
      notificationTitle,
      notificationMessage,
      notificationDate,
    );

    notificationItem.append(
      notificationReadIndicator,
      notificationInfoContainer,
    );

    notificationUL.append(notificationItem);
  });
}
