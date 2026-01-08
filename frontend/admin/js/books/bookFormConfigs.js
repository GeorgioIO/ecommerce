export const bookFormConfigs = {
  fields: [
    {
      name: "Book_id",
      key: "id",
      tag: "input",
      type: "number",
      disabled: true,
    },
    {
      name: "ISBN",
      key: "isbn",
      tag: "input",
      type: "text",
      disabled: false,
      required: true,
    },
    {
      name: "Sku",
      key: "sku",
      tag: "input",
      type: "text",
      disabled: false,

      required: true,
    },
    {
      name: "Title",
      key: "title",
      tag: "input",
      type: "text",
      disabled: false,

      required: false,
    },
    {
      name: "Language",
      key: "language",
      tag: "select",
      source: "languages",
      disabled: false,

      required: true,
    },
    {
      name: "Author",
      key: "author_id",
      tag: "select",
      source: "authors",
      disabled: false,

      required: true,
    },
    {
      name: "Format",
      key: "format_id",
      tag: "select",
      source: "formats",
      disabled: false,

      required: true,
    },
    {
      name: "Cover",
      key: "cover_image",
      tag: "input",
      type: "file",
      disabled: false,

      required: false,
    },
    {
      name: "Description",
      key: "description",
      tag: "textarea",
      disabled: false,

      required: false,
    },
    {
      name: "Genre",
      key: "genre_id",
      tag: "select",
      source: "genres",
      disabled: false,

      required: true,
    },
    {
      name: "Quantity",
      key: "stock_quantity",
      tag: "input",
      type: "number",
      disabled: false,

      required: true,
    },
    {
      name: "Price",
      key: "price",
      tag: "input",
      type: "number",
      step: "0.01",
      disabled: false,

      required: true,
    },
  ],
};
