export async function loadAdminNotifications_DB() {
  const result = await fetch(
    "../../backend/notifications/admin_notifications/get_admin_notifications.php",
  );

  return result.json();
}

export async function MarkAllNotificationRead_DB() {
  const result = await fetch(
    "../../backend/notifications/admin_notifications/mark_all_not_as_read.php",
  );

  return result.json();
  // console.log(result.text());
}

export async function getUnreadNotificationCount() {
  const result = await fetch(
    "../../backend/notifications/admin_notifications/get_unread_notification_count.php",
  );

  return result.json();
  // console.log(result.text());
}
