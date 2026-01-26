export async function sendEmail() {
  const result = await fetch("../../backend/email/send_email.php");

  return result.json();
}
