let sessionCache = null;

export async function getSession() {
  if (sessionCache) return sessionCache;

  const response = await fetch("../../../backend/auth/get_session.php");
  const data = response.json();
  sessionCache = data;

  return data;
  // console.log(response.text());
}
