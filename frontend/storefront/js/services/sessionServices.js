export async function getSession() {
  const response = await fetch("../../../backend/auth/get_session.php");

  return response.json();
}
