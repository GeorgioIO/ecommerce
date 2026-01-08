export function populateSelectLanguages(selectEl) {
  const optDefault = new Option("Select Language", "");
  const opt1 = new Option("English", "English");
  const opt2 = new Option("French", "French");

  selectEl.append(optDefault, opt1, opt2);
}
