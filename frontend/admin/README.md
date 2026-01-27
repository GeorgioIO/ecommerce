# 📊 Admin Dashboard - BookNest

![Admin Logging in to dashboard](/assets/readme-assets/logging-in-gif.gif)

The **Admin Dashboard** is the core management system of the BookNest e-commerce platform. It is a fully custom-built internal tool designed to allow admins to **manage products, customers, orders, inventory** efficiently and safely.

This dashboard was built fro **scratch** using Vanilla PHP, MySQL, JavaScript, HTML, CSS\*\* without relying on frameworks to demonstrate a strong understanding of backend logic, data integrity, and frontend achitecture.

## 🎯 Purpose of the Dashboard

The dashboard is designed to handle **real- world e-commerce operations**, including:

- Managing books and inventory
- Handling customer data and addresses
- Creating and editing orders with stock validation
- Monitoring sales and system statistics
- Receiving admin notifications (orders, stock alerts)

It reflects how **real admin panels work** fcusing on data consistency, validation, and operation safety rather than just UI.

## 🧩 Main Features

### 📚 Books Management

![Books Section](/assets/readme-assets/dashboard-books.png)

- Add, Edit, and delete books
- Manage:
  - ISBN, SKU
  - Price
  - Stock Quantity
  - Formats, Genres, Authors
- Automatic stock status updates (in stock, out of stock)
- Pagination
- Image upload handling

#### Process of adding a book

- Admin click add new order
  ![Admin click new order](/assets/readme-assets/proccess-add-book-1.png)

- Operation form will open

```
/*
    Check rest of code here in adminUIController.js , lines are removed to scope the focus on the operation happening
*/

const entityHandlers = {
  book: {
    showAdd: showBookAddForm,
    showEdit: showBookEditForm,
    resetForm: resetBookForm,
    addEntity: addBook_DB,
    updateEntity: update_book_DB,
    delete: deleteBook_DB,
    loader: loadBooks,
    dataCollector: collectBookFormData,
    dataValidator: validateBookData,
  },
  author: {
    showAdd: showAuthorAddForm,
    showEdit: showAuthorEditForm,
    resetForm: resetAuthorForm,
    addEntity: addAuthor_DB,
    updateEntity: update_author_DB,
    delete: delete_Author_DB,
    loader: loadAuthors,
    dataCollector: collectAuthorFormData,
    dataValidator: validateAuthorData,
  },
  genre: {
    showAdd: showGenreAddForm,
    showEdit: showGenreEditForm,
    resetForm: resetGenreForm,
    addEntity: addGenre_DB,
    updateEntity: updateGenre_DB,
    delete: deleteGenre_DB,
    loader: loadGenres,
    dataCollector: collectGenreFormData,
    dataValidator: validateGenreData,
  },
  customer: {
    showView: showCustomerViewForm,
    resetForm: resetCustomerForm,
    loader: loadCustomers,
  },
  order: {
    showAdd: showOrderAddForm,
    showEdit: showOrderEditForm,
    resetForm: resetOrderForm,
    addEntity: addOrder_DB,
    updateEntity: updateOrder_DB,
    loader: loadOrders,
    dataCollector: collectOrderFormData,
    dataValidator: validateOrderData,
  },
};

document.addEventListener("click", async (e) => {
  const openOperationFormButton = e.target.closest(".open-operation-form"); // false
  /*
        Check rest of code here in adminUIController.js
  */

  if (openOperationFormButton) {
    const { entity, id, intent } = openOperationFormButton.dataset;
    const openForm = entityHandlers?.[entity]?.[intent];
    if (openForm) {
      await openForm(id);
    }
  }

});
```

- based on my routing system in **AdminUIController.js** automatically through using the dataset attached to the open button , i will get from the entityHandler Object of each entity object the function needed to open the form with specific configuration **showAdd: showBookAddForm**

- User will fill data and then submit
  ![Admin fill data](/assets/readme-assets/process-add-book-2.png)
  ![Admin submit](/assets/readme-assets/process-add-book-3.png)

```
document.addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const { entity, mode } = form.dataset;
  // Get data collector and collect - entity
  const entityDataCollector = entityHandlers?.[entity]?.dataCollector;
  const data = entityDataCollector(form);

  // Get data validator and validate - entity
  const entityDataValidator = entityHandlers?.[entity]?.dataValidator;
  const validationResult = entityDataValidator(data);

  if (!validationResult.valid) {
    showMessageLog("error", validationResult.error);
    return;
  }

  // Get entity loader
  const loadEntityElements = entityHandlers?.[entity]?.loader;

  // if MODE is ADD
  if (mode === "add") {
    const addEntity = entityHandlers?.[entity]?.addEntity;

    const addEntityResult = await addEntity(data);

    if (addEntityResult?.success) {
      showMessageLog("success", addEntityResult.message);
      removeSearchBox();
      listState.filters = {};
      listState.entity = entity;
      await loadEntityElements();
    } else {
      showMessageLog("error", addEntityResult.message);
    }
    // if MODE is EDIT (UPDATE)
  } else if (mode === "edit") {
    const updateEntity = entityHandlers?.[entity]?.updateEntity;document.addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const { entity, mode } = form.dataset;
  // Get data collector and collect - entity
  const entityDataCollector = entityHandlers?.[entity]?.dataCollector;
  const data = entityDataCollector(form);

  // Get data validator and validate - entity
  const entityDataValidator = entityHandlers?.[entity]?.dataValidator;
  const validationResult = entityDataValidator(data);

  if (!validationResult.valid) {
    showMessageLog("error", validationResult.error);
    return;
  }

  // Get entity loader
  const loadEntityElements = entityHandlers?.[entity]?.loader;

  // if MODE is ADD
  if (mode === "add") {
    const addEntity = entityHandlers?.[entity]?.addEntity;

    const addEntityResult = await addEntity(data);

    if (addEntityResult?.success) {
      showMessageLog("success", addEntityResult.message);
      removeSearchBox();
      listState.filters = {};
      listState.entity = entity;
      await loadEntityElements();
    } else {
      showMessageLog("error", addEntityResult.message);
    }
    // if MODE is EDIT (UPDATE)
  } else if (mode === "edit") {
    const updateEntity = entityHandlers?.[entity]?.updateEntity;

    const updateEntityResult = await updateEntity(data);
    if (updateEntityResult?.success) {
      showMessageLog("success", updateEntityResult.message);
      listState.filters = {};
      listState.entity = entity;
      await loadEntityElements();
    } else {
      showMessageLog("error", updateEntityResult.message);
    }
  }
});

    const updateEntityResult = await updateEntity(data);
    if (updateEntityResult?.success) {
      showMessageLog("success", updateEntityResult.message);
      listState.filters = {};
      listState.entity = entity;
      await loadEntityElements();
    } else {
      showMessageLog("error", updateEntityResult.message);
    }
  }
});

```

- Using same entity handler routing system i use , automatically functions needed for that specific entity will be used

- On Submit , operation either fail or succeed and admin will get a message
  ![Operation succeeded](/assets/readme-assets/process-add-book-4.png)

### 👥 Customers Management

![Customers Section](/assets/readme-assets/dashboard-customers.png)

- Admin is shown also as a customer in the first row
- View customers and their details
- View customer addresses (multple addresses supported)
- View customer order statistics (total orders, total spent)
- Read-only customer data to prevent accidental corruption

Admin have limited control on customers section , for example adding a customer is not allowed or changing a customer data

### 📃 Orders Management (Advanced)

![Orders Section](/assets/readme-assets/dashboard-orders.png)

- Create orders manually (Admin made orders) with notification and emailing system.
- Edit existing orders with **strict rules:**
  - Only editable if order status allow it
  - Order lines comparison (insert / update / delete)
  - Stock difference calculation
  - Automatic stock rollback on failure
- Order composition :
  - Order metadata (id, code , customer name , status , date added , price)
  - Shipping address (id , first name , last name , email , phone number , state , city , address line 1 , address line 2 , additional notes)
  - Order lines
- Usage of **database transactions** to esnure consistency and the full database safety in case of failures

### Process of adding an order

Case : admin will add a special order for a already existing customer

- Customer name is **Georgio Jabbour** wuth the email **georgio@test.com**
- Customer wants Sun Tzu Art of War , Atomic Habits, On Palestine
- Customer wants his first address

- Admin click on add new order

![Admin click add new order](/assets/readme-assets/process-add-order.png)

- Operation form will appear
- All existing customers will be fetched and shown in a select box
- Admin pick a specific customer

![Operation form shown](/assets/readme-assets/process-add-order-2.png)

- On change , existing addresses for that customer will be fetched from backend

![existing addresses](/assets/readme-assets/process-add-order-3.png)

- Based on the address picked , its data will be fetched and then loaded into the fields

![address fetched](/assets/readme-assets/process-add-order-4.png)

- Admin now search for books
- Admin picks wanted books
- Prices will be calculated
- On submit Stock will be handled

![Books searched order is submitted](/assets/readme-assets/process-add-order-5.gif)

- Admin will receive notification in the dashboard and email
  ![Notifications](/assets/readme-assets/process-add-order-5.png)
  as we can see admin received that **Sun Tzu Art of War** is low stock ,
  **Atomic Habits** is out of stock , and also an order is placed , and we can see the order is shown in **recent five orders table**

- Customer will receive an email on the shipping address email

![Email to customer](/assets/readme-assets/process-add-order-6.png)

### Authors & Genres Management

![Authors Section](/assets/readme-assets/dashboard-authors.png)
![Genres Section](/assets/readme-assets/dashboard-genres.png)

- Add, Edit, Delete Authors / Genres.
- Blocked delete of Authors / Genres with existing child rows.
- Cascading books showing of specific Authors / Genres.
