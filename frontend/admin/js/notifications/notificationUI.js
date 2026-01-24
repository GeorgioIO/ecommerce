import { loadBooks, showBookEditForm } from "../books/booksUI.js";
import { loadOrders, showOrderEditForm } from "../orders/orderUI.js";
import { changeSidebarSection } from "../UIhelpers.js";
import {
  MarkAllNotificationRead_DB,
  getUnreadNotificationCount,
  loadAdminNotifications_DB,
} from "./notificationsServices.js";

document.addEventListener("click", async (e) => {
  const notificationContainer = document.querySelector(
    ".notifications-container",
  );
  const markAllAsReadButton = e.target.closest("#mark-all-as-read-button");
  const notificationItem = e.target.closest(".notification-item");

  if (markAllAsReadButton) {
    const notificationItems =
      notificationContainer.querySelectorAll(".notification-item");

    if (notificationItems) {
      const result = await MarkAllNotificationRead_DB();
      loadNotifications();
      changeNotificationCountBadge();
    }
  }

  if (notificationItem) {
    const { entity, entityid } = notificationItem.dataset;

    if (entity === "order") {
      changeSidebarSection(entity);
      loadOrders();
      showOrderEditForm(entityid);
    } else if (entity === "book") {
      changeSidebarSection(entity);
      loadBooks();
      showBookEditForm(entityid);
    }
  }
});

export async function changeNotificationCountBadge() {
  const notificationBadge = document.querySelector(".notification-badge");

  const notificationCount = await getUnreadNotificationCount();

  if (notificationCount.value === 0) {
    notificationBadge.style.display = "none";
  } else if (notificationCount.value > 9) {
    notificationBadge.style.display = "flex";
    notificationBadge.textContent = notificationCount.value;
  } else {
    notificationBadge.style.display = "flex";
    notificationBadge.textContent = "+9";
  }
}

export async function loadNotifications() {
  const data = await loadAdminNotifications_DB();

  // Badge

  const notificationUL = document.querySelector(".notifications-ul");
  notificationUL.innerHTML = "";

  data.forEach((notification) => {
    const notificationItem = document.createElement("li");
    notificationItem.classList.add("notification-item");
    notificationItem.dataset.notificationid = notification.id;
    notificationItem.dataset.entity = notification.entity;
    notificationItem.dataset.entityid = notification.entity_id;

    const notificationReadIndicator = document.createElement("div");
    notificationReadIndicator.classList.add("notification-read-indicator");
    notificationReadIndicator.classList.add(
      parseInt(notification.is_read) === 1
        ? "read-notification"
        : "unread-notification",
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
