import { handleEntityImageElement, toggleDiscountInfo } from "../UIhelpers.js";

export function hydrateBookForm(form, data) {
  Object.keys(data).forEach((key) => {
    if (key === "is_deleted") return;
    const input = form.querySelector(`#${key}`);
    if (!input || input.type === "file") return;

    if (data.is_onSale === 1) {
      input.checked = true;
      toggleDiscountInfo();
    }
    input.value = data[key];
  });

  if (data.cover_image) {
    handleEntityImageElement("set", data.cover_image);
  }
}

/*

Object.keys take an object and return an array of its own property names
data = {
      keys    values 
  author_id : 4,
  cover_image : "abcd.png",
  description : "adabababa"
  format_id :
  genre_id :
  id :
  isbn :
  language :
  price :
  sku : 
  stock_quantity :
  title :
}

Object.keys(data) = ["author_id" , "cover_image" , "description" , "format_id" , "genre_id" , "id" , "isbn" , "language" , "price" , "sku" , "stock_quantity" , "title"]

*/
