<p align="center">
    <a href="https://vuejs.org" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg" width="150" alt="Vue.js Logo"></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo"></a> </p> 

About the Project <br>
The Order Management System is a web application built to manage daily user orders with features for viewing, creating, editing, and deleting orders and products. Built using a Vue.js frontend and a Laravel backend, the application uses MySQL for data storage and is containerized with Docker for easy deployment.

Features
Order Management: Create, view, update, and delete orders.
Product Management: Add and manage products associated with orders.
Filtering & Searching: Filter orders by name, description, and date.
RESTful API: Fully documented API for order and product operations.
Tech Stack
Frontend: Vue.js (with Vuetify for UI components)
Backend: Laravel (PHP)
Database: MySQL
Containerization: Docker and Docker Compose

Installation
Prerequisites
Ensure you have the following installed:
    -Docker
    -Docker Compose
    -Git

Getting Started
1. Clone the Repository: <br>
       git clone https://github.com/hotdaduska/Iliad-Project.git <br>
       cd Iliad-Project

2. Set Up Environment Variables
        Copy the .env.example file to .env and update the environment variables as needed: <br>
        cp .env.example .env

3. Docker Compose Setup
        Build and start the Docker containers <br> 
        docker-compose up --build

4. Install Composer and npm Dependencies
        Run these commands inside the app and node containers to install backend and frontend dependencies: <br>
        docker-compose exec app composer install <br>
        docker-compose exec node npm install

5. Database Setup <br>
   Run migrations to create the database schema: <br>
   docker-compose exec app php artisan migrate <br><br>

7. Start the Development Servers

8. Database Access
   phpMyAdmin is available at http://localhost:8080 for ease of access and visualization, with the credentials set in the .env file.


Usage

Order Management
Navigate to the main order list to view, filter, and manage orders. <br>
Use the "Add Order" button to create new orders. <br>

Product Management <br>
Use the "Add Product" button to create new products in the inventory. <br>

Filter and Search <br>
Use filters for name, description, or date to find specific orders. <br>



Troubleshooting <br>
Database Connection Issues: Ensure Docker containers are running, and check database credentials in the .env file. <br>
Port Conflicts: Change ports in docker-compose.yml if conflicts arise. <br>





    
