export const genreFormConfigs = {
  fields: [
    {
      name: "genre_id",
      key: "id",
      tag: "input",
      type: "number",
      disabled: true,
    },
    {
      name: "Name",
      key: "name",
      tag: "input",
      type: "text",
      disabled: false,
      required: true,
    },
    {
      name: "Image",
      key: "image",
      tag: "input",
      type: "file",
      disabled: false,
      required: false,
    },
  ],
};
