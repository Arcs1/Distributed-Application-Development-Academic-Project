# FasTuga - Distributed Application Development Academic Project

## Project Overview
**FasTuga** is an academic project designed as a platform for the fictional "FasTuga" restaurant. It consists of a **Single Page Application (SPA)** for customer interaction, where users can place orders, make payments, and track their order status, along with backend and WebSocket components to manage restaurant operations.

For more detailed information, please refer to the [project requirements](2022-23-EI-DAD-Project.pdf).

### Key Features
- **Frontend (Vue.js):**
  - Dynamic user roles with Pinia for state management.
  - Real-time notifications via WebSockets.
  - An interface for placing and tracking orders.

- **Backend (Laravel):**
  - RESTful API to manage restaurant operations.
  - User authentication and points-based discount system.
  - Relational database (MySQL) integration for data persistence.

- **WebSockets (Node.js):**
  - Real-time communication between managers, chefs, delivery employees, and customers.
  - Notifications for order readiness.

This project was developed to showcase skills in full-stack web development, including real-time communication, user roles, state management, and database interaction.

---

## Technologies Used
### Core Frameworks and Tools
- **Frontend:**
  - Vue.js 3
  - Pinia (state management)
  - Vue Router
  - Axios
  - Chart.js

- **Backend:**
  - Laravel 9
  - Laravel Passport (authentication)
  - MySQL

- **WebSockets:**
  - Node.js
  - Socket.IO

### Build and Development
- **Frontend:** Vite
- **Backend:** Laragon (local PHP development environment)

---

## Environment Setup

### Prerequisites
- **Frontend and WebSockets:**
  - [Node.js](https://nodejs.org/en) (v16 or above)

- **Backend:**
  - [Laragon](https://laragon.org/) or any similar development environment


---

### Step-by-Step Instructions

1. **Clone the Repository:**
     ```bash
     git clone https://github.com/Arcs1/Distributed-Application-Development-Academic-Project
     ```

2. **Backend Setup:**
   - In Laragon's terminal, navigate to the backend folder:
     ```bash
     cd backend
     ```
   - Install dependencies:
     ```bash
     composer install
     ```
   - Configure environment variables:
     - Rename backend's `.env.example` to `.env` and update database credentials.
     - `.env.example` assumes you have a database called `laravel` already created. You can change the database you want to use by changing the value of the `DB_DATABASE` variable.
   - Run the following commands:
     ```bash
     php artisan key:generate
     php artisan storage:link
     php artisan migrate
     php artisan db:seed
     ```
     Choose between a small or large amount of dummy data when prompted.

     ```bash
     php artisan passport:install
     ```
     Copy the Client Secret of the second ID and paste it as the value of the `PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET` variable.


3. **Frontend Setup:**
   - In a terminal, navigate to the frontend folder:
     ```bash
     cd frontend
     ```
   - Install dependencies:
     ```bash
     npm install
     ```
   - Configure environment variables:
     - Rename frontend's `.env.example` to `.env`.
   - Start the development server:
     ```bash
     npm run dev
     ```
     The frontend will be accessible at http://localhost:5173.
   

4. **WebSocket Server Setup:**
   - In another terminal, navigate to the WebSockets folder:
     ```bash
     cd websockets
     ```
   - Install dependencies:
     ```bash
     npm install
     ```
   - Start the WebSocket server:
     ```bash
     node index
     ```

---

## Project Workflow
### User Roles
1. **Customer:**
    - Browse the menu, place orders, and receive notifications when orders are ready.
    - Orders are prepaid, meaning only successful payments allow order creation.
    - Earn points based on spending, with 1 point awarded for every 10 € spent.
    - Use accumulated points (blocks of 10 points) for discounts on future orders.
    - Can cancel orders before they are delivered, with refunds processed accordingly.

2. **Chef:**
    - Receive notifications when hot dishes are ordered and require preparation.
    - Mark dishes as “Preparing” when they start cooking and as “Ready” when cooking is finished.
    - Hot dishes require cooking, while cold dishes, drinks, and desserts do not.

3. **Delivery Employee:**
    - Receive notifications when hot dishes are ready for delivery.
    - Complete orders (which can include hot and cold dishes, drinks, and desserts) and mark them as “Ready” once delivered.
    - Track the status of each order and ensure that the customer receives their meal.
    - Responsible for confirming deliveries, and if completed, the order status changes to “Delivered.”

4. **Manager:**
    - Manage user accounts, including the ability to create, block, unblock, or delete employees and customers.
    - Oversee the menu, editing product details, adding or removing items, and setting prices.
    - Can cancel any order if deemed necessary, issuing refunds and revoking any points awarded for cancelled orders.
    - Access business statistics and platform usage information to monitor restaurant operations.

5. **Public Board:**
    - Display all orders that are “Ready” but not yet delivered, providing a public view for customers to track the status of orders.
    - Include a ticket number for each order, allowing customers and employees to track progress efficiently.
---

## Deployment
This project was deployed on a virtual machine provided by the school. Files were transferred to the VM and run in the school's network via VPN. The deployment process involved placing the project files on the VM and running the necessary backend, frontend, and WebSocket services.

---

## Notes
- This project was built for academic purposes to showcase the integration of modern web technologies.
- The backend is configured to run locally with Laragon and MySQL database for development purposes. Ensure the .env files are properly configured for local development.
- For production deployment, environment settings and configurations may need to be adjusted accordingly.
- Contributions are not accepted for this repository. It serves as a showcase of the work completed for the project.
- The application was deployed within a private network for demonstration purposes, accessible only through a VPN provided by the school.


---

## License
This project is unlicensed and is made publicly available **for showcase purposes only**.
