const languagesOptions = [
  {
    label: "English",
    value: "English",
  },
  {
    label: "French",
    value: "French",
  },
];

const sortingOptions = [
  {
    label: "Alphabetically A-Z",
    value: "alpha-a-z",
  },
  {
    label: "Alphabetically Z-A",
    value: "alpha-z-a",
  },
  {
    label: "Price, Low to High",
    value: "low-to-high-price",
  },
  {
    label: "Price, High to Low",
    value: "high-to-low-price",
  },
  {
    label: "Date, Old to New",
    value: "old-to-new-date",
  },
  {
    label: "Date, New to Old",
    value: "new-to-old-date",
  },
];

export function buildFilteringForm() {
  // Filtering and sort options
  const filteringForm = document.createElement("form");
  filteringForm.id = "filtering-form";

  // Sort by option
  const sortByContainer = document.createElement("div");
  sortByContainer.classList.add("form-row");

  const sortByTitle = document.createElement("p");
  sortByTitle.textContent = "Sort By:";

  const sortBySelect = document.createElement("select");
  sortBySelect.id = "sortOption";

  sortingOptions.forEach((option) => {
    const optionElement = document.createElement("option");
    if (option.label === "Alphabetically A-Z") optionElement.selected = true;
    optionElement.textContent = option.label;
    optionElement.value = option.value;

    sortBySelect.append(optionElement);
  });

  sortByContainer.append(sortByTitle, sortBySelect);

  // Title
  const titleContainer = document.createElement("div");
  titleContainer.classList.add("form-row");

  const titleLabel = document.createElement("label");
  titleLabel.textContent = "Title";
  titleLabel.id = "title-label";
  titleLabel.htmlFor = "title-input";

  const titleInput = document.createElement("input");
  titleInput.id = "title";

  titleContainer.append(titleLabel, titleInput);

  // Stock options
  const stockContainer = document.createElement("div");
  stockContainer.classList.add("form-row");

  const stockTitle = document.createElement("p");
  stockTitle.textContent = "Availability";

  const availableStockContainer = document.createElement("div");
  availableStockContainer.classList.add("availability-stock-container");
  const outOfStockContainer = document.createElement("div");
  outOfStockContainer.classList.add("availability-stock-container");

  const availableCheckbox = document.createElement("input");
  availableCheckbox.type = "radio";
  availableCheckbox.id = "available-checkbox";
  availableCheckbox.name = "availability";

  const availableLabel = document.createElement("label");
  availableLabel.id = "available-stock-label";
  availableLabel.textContent = "Available";
  availableLabel.htmlFor = "available-checkbox";

  availableStockContainer.append(availableCheckbox, availableLabel);

  const outOfStockCheckbox = document.createElement("input");
  outOfStockCheckbox.type = "radio";
  outOfStockCheckbox.id = "outofstock-checkbox";
  outOfStockCheckbox.name = "availability";

  const outOfStockLabel = document.createElement("label");
  outOfStockLabel.id = "out-of-stock-label";
  outOfStockLabel.textContent = "Out of stock";
  outOfStockLabel.htmlFor = "outofstocks-checkbox";

  outOfStockContainer.append(outOfStockCheckbox, outOfStockLabel);

  stockContainer.append(
    stockTitle,
    availableStockContainer,
    outOfStockContainer,
  );

  // Price
  const priceContainer = document.createElement("div");
  priceContainer.classList.add("form-row");

  const priceTitle = document.createElement("p");
  priceTitle.textContent = "Price";

  const priceInnerContainer = document.createElement("div");
  priceInnerContainer.classList.add("price-inner-container");

  const fromPrice = document.createElement("input");
  fromPrice.value = 0;
  fromPrice.type = "number";
  fromPrice.min = "0";
  fromPrice.id = "minPrice";
  fromPrice.name = "from-price";

  const toText = document.createElement("p");
  toText.textContent = "to";

  const toPrice = document.createElement("input");
  toPrice.type = "number";
  toPrice.id = "maxPrice";
  toPrice.name = "to-price";

  priceInnerContainer.append(fromPrice, toText, toPrice);

  priceContainer.append(priceTitle, priceInnerContainer);

  // Author options
  const authorContainer = document.createElement("div");
  authorContainer.classList.add("form-row");

  const authorLabel = document.createElement("label");
  authorLabel.textContent = "Author:";
  authorLabel.htmlFor = "author";

  const authorSelect = document.createElement("select");
  authorSelect.id = "author";

  const defaultAuthorOption = document.createElement("option");
  defaultAuthorOption.textContent = "--";
  defaultAuthorOption.selected = true;

  authorSelect.append(defaultAuthorOption);

  authorContainer.append(authorLabel, authorSelect);

  // Genre options
  const genreContainer = document.createElement("div");
  genreContainer.classList.add("form-row");

  const genreLabel = document.createElement("label");
  genreLabel.textContent = "Genre:";
  genreLabel.htmlFor = "genre";

  const genreSelect = document.createElement("select");
  genreSelect.id = "genre";

  const defaultGenreOption = document.createElement("option");
  defaultGenreOption.textContent = "--";
  defaultGenreOption.selected = true;

  genreSelect.append(defaultGenreOption);

  genreContainer.append(genreLabel, genreSelect);

  // Format options
  const formatContainer = document.createElement("div");
  formatContainer.classList.add("form-row");

  const formatLabel = document.createElement("label");
  formatLabel.textContent = "Format:";
  formatLabel.htmlFor = "format";

  const formatSelect = document.createElement("select");
  formatSelect.id = "format";

  const defaultFormatOption = document.createElement("option");
  defaultFormatOption.textContent = "--";
  defaultFormatOption.selected = true;

  formatSelect.append(defaultFormatOption);

  formatContainer.append(formatLabel, formatSelect);

  // Language options
  const languageContainer = document.createElement("div");
  languageContainer.classList.add("form-row");

  const languageLabel = document.createElement("label");
  languageLabel.textContent = "Language:";
  languageLabel.htmlFor = "language";

  const languageSelect = document.createElement("select");
  languageSelect.id = "language";

  const defaultLanguageOption = document.createElement("option");
  defaultLanguageOption.textContent = "--";
  defaultLanguageOption.selected = true;

  languageSelect.append(defaultLanguageOption);

  languagesOptions.forEach((option) => {
    const optionElement = document.createElement("option");
    optionElement.textContent = option.label;
    optionElement.value = option.value;

    languageSelect.append(optionElement);
  });

  languageContainer.append(languageLabel, languageSelect);

  filteringForm.append(
    sortByContainer,
    stockContainer,
    titleContainer,
    priceContainer,
    authorContainer,
    genreContainer,
    formatContainer,
    languageContainer,
  );

  return filteringForm;
}
