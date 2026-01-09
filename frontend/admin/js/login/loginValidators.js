export function validateLoginData(data) {
  /*
    Mainly we have two things to vallidate
    email and password
    email : required , check if its an actual email
    password : required
    */
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  const email = data.email.trim();

  if (!email || email === "") {
    return {
      valid: false,
      error: "Email cannot be empty",
    };
  }

  if (!emailRegex.test(email)) {
    return {
      valid: false,
      error: "Email is Invalid",
    };
  }

  const password = data.password.trim();
  if (!password || password === "") {
    return {
      valid: false,
      error: "Password cannot be empty",
    };
  }

  return { valid: true };
}
