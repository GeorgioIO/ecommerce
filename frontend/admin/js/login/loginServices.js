export async function checkAdminLogin_DB(adminData) {
  const formData = new FormData();
  formData.append("email", adminData.email);
  formData.append("password", adminData.password);

  const result = await fetch("../../backend/auth/admin_login.php", {
    method: "POST",
    body: formData,
  });

  // alert(result.text());
  return result.json();
}
