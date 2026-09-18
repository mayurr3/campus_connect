# 🎓 Campus Connect — College Event Management System

A centralized, responsive college event management web application developed using **HTML5, CSS3, JavaScript, PHP, and MySQL**.

Designed with a modern dark glassmorphism aesthetic, featuring role-based portals for both students and administrators.

---

## 🌟 Key Features

### 👨‍🎓 Student Portal
- **User Authentication**: Secure student registration and login with BCRYPT password hashing and session management.
- **Event Discovery**: Browse upcoming college events with category filtering and real-time live search.
- **One-Click Event Registration**: Quick registration with automatic duplicate prevention.
- **Personal Dashboard**: View registered events, dates, venue details, and booking status.
- **Profile Management**: View and update student profile information (contact, course, semester).

### 🛠️ Administrator Portal
- **Admin Dashboard**: Real-time KPI statistics (Total Students, Total Events, Total Registrations).
- **Event Management (CRUD)**: Create new events with banner images, edit event details, or delete events.
- **Registrations Registry**: View detailed master registration list with student and event records.

---

## 💻 Tech Stack

- **Frontend**: HTML5, Vanilla CSS3 (Custom Glassmorphism & Neon Design System), JavaScript (ES6)
- **Backend**: PHP 8.x
- **Database**: MySQL 8.x
- **Local Server**: WAMPServer / XAMPP (Apache + MySQL)

---

## 🚀 Setup & Installation

### Prerequisites
- [WAMPServer](https://www.wampserver.com/) or [XAMPP](https://www.apachefriends.org/) installed on your machine.
- PHP 8.0+ and MySQL 5.7+ / 8.0+.

### Steps
1. **Clone or Download the Repository**:
   ```bash
   git clone https://github.com/<your-username>/campus_connect.git
   ```
2. **Move to Web Directory**:
   - Place the `campus_connect` folder in your server's root directory:
     - For WAMP: `C:\wamp64\www\campus_connect`
     - For XAMPP: `C:\xampp\htdocs\campus_connect`

3. **Import Database**:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `campus_connect`.
   - Select the `campus_connect` database and go to the **Import** tab.
   - Choose the file [`database/campus_connect.sql`](database/campus_connect.sql) and click **Import**.

4. **Configure Database Connection**:
   - Verify database settings in [`config/database.php`](config/database.php):
     ```php
     $db_host = "127.0.0.1";
     $db_user = "root";
     $db_pass = ""; // default for WAMP/XAMPP
     $db_name = "campus_connect";
     ```

5. **Run the Application**:
   - Open your browser and navigate to:
     ```
     http://localhost/campus_connect/
     ```

---

## 🗄️ Database Architecture

The schema contains 4 interconnected tables:
- **`users`**: Stores registered student profiles and credentials.
- **`admins`**: Stores authorized administrator credentials.
- **`events`**: Stores event titles, descriptions, categories, schedules, venues, and banners.
- **`registrations`**: Connects students to events with foreign keys and unique constraints (`user_id`, `event_id`) to prevent duplicates.

---

## 📄 License
This project is open source and available for academic and educational purposes.
