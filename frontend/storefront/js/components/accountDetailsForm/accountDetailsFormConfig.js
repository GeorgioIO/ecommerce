export const accountDetailsConfig = {
  fields: [
    {
      name: "username",
      labelText: "User name",
      key: "name",
      tag: "input",
      type: "text",
      disabled: false,
      required: false,
    },
    {
      name: "email",
      labelText: "Email",
      key: "email",
      tag: "input",
      type: "email",
      disabled: false,
      required: false,
    },
    {
      name: "phone_number",
      labelText: "Phone Number",
      key: "phone_number",
      tag: "input",
      type: "text",
      disabled: false,
      required: false,
    },
  ],
};

export const passwordConfig = {
  fields: [
    {
      name: "current_passowrd",
      labelText: "Current password",
      key: "password",
      tag: "input",
      type: "password",
      disabled: false,
      required: false,
    },
    {
      name: "new_password",
      labelText: "New password",
      key: "new_password",
      tag: "input",
      type: "password",
      disabled: false,
      required: false,
    },
    {
      name: "confirm_password",
      labelText: "Confirm Password",
      key: "confirm_password",
      tag: "input",
      type: "password",
      disabled: false,
      required: false,
    },
  ],
};
