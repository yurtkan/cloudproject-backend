# Nyan Cat Asian House - Backend API

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

This is the backend server and RESTful API built in native PHP for the **Nyan Cat Asian House** mobile application. It handles all the data transactions, menu items, reservations, user authentication, and image hosting.

## 📱 Frontend Application

This backend powers the Flutter mobile app. You can find the frontend repository here:
🔗 **[cloudproject (Frontend)](https://github.com/yurtkan/cloudproject)**

## ✨ Key Features

- **RESTful API Endpoints:** Provides robust endpoints for the frontend app to fetch food menus, place orders, and manage table reservations.
- **MVC Architecture:** Structured with dedicated `Controller` and `Model` directories for clean separation of concerns.
- **Image Hosting:** Serves product and restaurant images directly from the `images/` directory.
- **Authentication Handling:** Manages user login validation and registration.
- **Native PHP:** Lightweight backend built with native PHP, avoiding heavy framework dependencies.

## 📂 Project Structure

```text
cloudproject-backend/
├── api/                    # Core API files
│   ├── Controller/         # Request handling and business logic
│   ├── Model/              # Database interaction and data structures
│   ├── inc/                # Includes and shared configuration (e.g., DB config)
│   └── index.php           # API Router / Entry Point
├── images/                 # Hosted images for the mobile app
├── index.php               # Landing and server information page
└── README.md               # Project documentation
```

## 🚀 Getting Started

### Prerequisites

- PHP (7.4 or newer recommended)
- Web Server (Apache, Nginx, or similar)
- MySQL / MariaDB (or compatible relational database)

### Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/yurtkan/cloudproject-backend.git
   ```
2. Place the folder inside your web server's root directory (e.g., `htdocs`, `www`, or `html`).
3. Import your database schema (if provided) into your MySQL database.
4. Configure your database connection settings inside the `api/inc/` directory.
5. Ensure the web server has proper read/write permissions for the project directory.
6. Test your server configuration by visiting the root URL in your browser.

## 📝 License

This project is licensed under the **Creative Commons Attribution-NonCommercial 4.0 International License (CC BY-NC 4.0)**.

This standard license allows you to share and adapt the material for personal, educational, and fair use purposes, provided you give appropriate credit. **You may not use the material for commercial purposes.**

For commercial use or reuse, a separate commercial license must be purchased from the author. See the `LICENSE` file for the full legal text.