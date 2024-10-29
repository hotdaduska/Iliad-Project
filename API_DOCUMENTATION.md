API Reference <br>
Base URL:
  All API endpoints use the base URL: http://localhost:8000/api/

<br><br>

ORDERS: <br>

- GET /orders <br>
Fetch a list of orders with optional filters. <br>

Query Parameters: <br>
name (string, optional): Filter by order name. <br>
description (string, optional): Filter by description. <br>
order_date_from (date, optional): Start of order date range. <br>
order_date_to (date, optional): End of order date range. <br>

Response:
[ <br>
  { <br>
    "id": 1, <br>
    "name": "Sample Order", <br>
    "description": "A test order", <br>
    "order_date": "2023-10-15", <br>
    "products": [ <br>
      { "id": 1, "name": "Product A", "quantity": 2 } <br>
    ] <br>
  } <br>
] <br>

<br><br>
- GET /orders/{id} <br>
Fetch a specific order by ID. <br>

Response: <br>
{ <br>
  "id": 1, <br>
  "name": "Sample Order", <br>
  "description": "A test order", <br>
  "order_date": "2023-10-15", <br>
  "products": [ <br>
    { "id": 1, "name": "Product A", "quantity": 2 } <br>
  ] <br>
} <br>

<br><br>

- POST /orders <br>
Create a new order with associated products. <br>
Body: <br>
{ <br>
  "name": "New Order", <br>
  "description": "Order description", <br>
  "order_date": "2023-10-29", <br>
  "products": [ <br>
    { "id": 1, "quantity": 3 } <br>
  ] <br>
} <br>

<br>

Response: <br>
{ <br>
  "id": 2, <br>
  "name": "New Order", <br>
  "description": "Order description", <br>
  "order_date": "2023-10-29", <br>
  "products": [ <br>
    { "id": 1, "name": "Product A", "quantity": 3 } <br>
  ] <br>
} <br>

<br><br><br>

PRODUCTS: <br>
GET /products <br>
Retrieve a list of all products. <br>
Response: <br>
[ <br>
  { <br>
    "id": 1, <br>
    "name": "Product A", <br>
    "price": 9.99 <br>
  } <br>
] <br>

<br><br>

POST /products <br>
Create a new product. <br>
Body: <br>
{ <br>
  "name": "Product A", <br>
  "price": 9.99 <br>
} <br>

<br>

Response: <br>
{ <br>
  "id": 1, <br>
  "name": "Product A", <br>
  "price": 9.99 <br>
} <br>

<br><br><br>

ORDER PRODUCT MANAGEMENT: <br>
POST /orders/{order}/products <br>
Attach multiple products to an order. <br>
Body: <br>
{ <br>
  "products": [ <br>
    { "product_id": 1, "quantity": 2 } <br>
  ] <br>
} <br>

<br><br>


