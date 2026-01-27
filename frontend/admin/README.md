# 📊 Admin Dashboard - BookNest

![Admin Logging in to dashboard](/assets/readme-assets/logging-in-gif.gif)

The **Admin Dashboard** is the core management system of the BookNest e-commerce platform. It is a fully custom-built internal tool designed to allow admins to **manage products, customers, orders, inventory** efficiently and safely.

This dashboard was built fro **scratch** using Vanilla PHP, MySQL, JavaScript, HTML, CSS\*\* without relying on frameworks to demonstrate a strong understanding of backend logic, data integrity, and frontend achitecture.

## 🎯 Purpose of the Dashboard

The dashboard is designed to handle \*\*real- world e-commerce operations, including:

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

### Authors & Genres Management

![Authors Section](/assets/readme-assets/dashboard-authors.png)
![Genres Section](/assets/readme-assets/dashboard-genres.png)

- Add, Edit, Delete Authors / Genres.
- Blocked delete of Authors / Genres with existing child rows.
- Cascading books showing of specific Authors / Genres.
