# Campus Connect — Project Viva & Defense Guide
> **Degree:** Bachelor of Computer Applications (BCA)  
> **Course Work:** 25 Marks Project Evaluation  
> **Project Title:** Campus Connect — College Event Management System  
> **Technologies:** HTML5, CSS3, JavaScript, PHP, MySQL, WAMPServer  

---

## 1. Project Summary (Say this when the teacher asks: "Tell me about your project")

> *"Campus Connect is a centralized college event management web application developed using pure HTML, CSS, JavaScript, PHP, and MySQL. It is hosted on a local WAMPServer environment.*  
> *The project provides two primary portals: a **Student Portal** and an **Administrator Portal**.*  
> *Students can register an account, browse upcoming college events by category, view event details, and register in one click while the system automatically prevents duplicate registrations. Students also have a personal dashboard to view their registration schedule and update their profile.*  
> *Administrators have an authorized control center with dynamic statistics (Total Students, Total Events, Total Registrations), full CRUD operations to publish, update, or delete events, and access to the master registration registry with attendance records."*

---

## 2. Technology Stack & Role of Each Technology

| Technology | Role in Campus Connect | Viva Explanation |
| :--- | :--- | :--- |
| **HTML5** | Structure & Semantics | Used to construct the layout, navigation bars, forms, event cards, and data tables. |
| **CSS3** | Aesthetics & Responsiveness | Uses modern dark glassmorphism, responsive CSS grid/flexbox, custom variables, and Figma-inspired violet/magenta neon accents without external heavy frameworks. |
| **JavaScript** | Client-Side Interaction | Handles live search by event title, instant category filtering, password visibility toggle, delete confirmation dialogs, and form input validation. |
| **PHP (8.x)** | Server-Side Processing | Handles business logic, session-based authentication (`$_SESSION`), input sanitization, password hashing (`BCRYPT`), and MySQL communication. |
| **MySQL (8.x)** | Relational Database | Stores persistent records in 4 normalized tables (`users`, `admins`, `events`, `registrations`) with foreign key cascade relationships. |
| **WAMPServer** | Local Stack Environment | Bundles the Apache HTTP Web Server, PHP processor, MySQL database server, and phpMyAdmin GUI. |

---

## 3. Database Architecture (Entity-Relationship Flow)

The database `campus_connect` has 4 tables:

```text
       +-------------------+               +-------------------+
       |       USERS       |               |      EVENTS       |
       |-------------------|               |-------------------|
       | id (PK)           |               | id (PK)           |
       | name              |               | title             |
       | email (UNIQUE)    |               | category          |
       | password (HASH)   |               | event_date        |
       | phone             |               | event_time        |
       | course            |               | location          |
       | semester          |               | image             |
       +---------+---------+               +---------+---------+
                 | 1                                 | 1
                 |                                   |
                 | many                              | many
       +---------v-----------------------------------v---------+
       |                     REGISTRATIONS                     |
       |-------------------------------------------------------|
       | id (PK)                                               |
       | user_id (FK -> users.id, ON DELETE CASCADE)           |
       | event_id (FK -> events.id, ON DELETE CASCADE)         |
       | registration_date                                     |
       | status                                                |
       | UNIQUE KEY: (user_id, event_id)                       |
       +-------------------------------------------------------+

       +-------------------+
       |      ADMINS       |
       |-------------------|
       | id (PK)           |
       | username (UNIQUE) |
       | password (HASH)   |
       +-------------------+
```

### Key Database Design Points:
1. **Many-to-Many Relationship:** One student can register for multiple events, and one event can have multiple students. The `registrations` table acts as the **junction table** (or bridge table) connecting them.
2. **Preventing Duplicates:** `UNIQUE KEY unique_registration (user_id, event_id)` ensures that even if a student clicks register repeatedly, the database will strictly disallow duplicate records.
3. **Referential Integrity & Cascading:** Both foreign keys have `ON DELETE CASCADE`. If the administrator deletes an event, all registrations for that event are automatically and safely deleted by the database.

---

## 4. Key Code Concepts You Need to Explain

### A. Database Connection (`config/database.php`)
```php
$conn = new mysqli("127.0.0.1", "root", "", "campus_connect", 3306);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
```
* **Teacher Question:** *"What does `mysqli` stand for and why do you use it?"*
* **Answer:** *"MySQLi stands for **MySQL Improved**. It provides object-oriented database access, supports prepared statements for high security, and is natively built into PHP."*

### B. Password Hashing (`register.php` & `login.php`)
```php
// Registration: Storing the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Login: Verifying the password
if (password_verify($password, $user['password'])) {
    // Password matches!
}
```
* **Teacher Question:** *"Are passwords stored in plain text? Why use `password_hash`?"*
* **Answer:** *"No, storing plain-text passwords is a major security vulnerability. We use PHP's built-in `password_hash()` with the **BCRYPT** algorithm. It generates a one-way mathematical hash with an automatic cryptographic salt, making it impossible for attackers or database administrators to see user passwords. During login, `password_verify()` safely checks if the input matches the stored hash."*

### C. Prepared Statements (SQL Injection Protection)
```php
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
```
* **Teacher Question:** *"What is SQL Injection and how does your code prevent it?"*
* **Answer:** *"SQL Injection happens when untrusted user input is directly concatenated into a query string, allowing an attacker to alter the query logic. We prevent this by using **Prepared Statements**. The database compiles the SQL template first, and then parameters are bound separately using `bind_param()`. The input is treated strictly as literal data, never as executable SQL code."*

### D. Session Security (`includes/auth.php` & `includes/admin_auth.php`)
```php
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
```
* **Teacher Question:** *"How does the website know who is logged in across different pages?"*
* **Answer:** *"HTTP is a stateless protocol. We use **PHP Sessions**. When the user logs in, a unique session ID is stored in a browser cookie (`PHPSESSID`), while their user credentials (`user_id`, `user_name`) are stored securely on the server. On every page, `require_student_login()` checks if `$_SESSION['user_id']` exists. If not, it automatically redirects the user to the login page."*

### E. SQL JOIN Query (`student/my_events.php`)
```sql
SELECT r.id AS reg_id, r.registration_date, r.status,
       e.title, e.event_date, e.event_time, e.location, e.category
FROM registrations r
INNER JOIN events e ON r.event_id = e.id
WHERE r.user_id = ?
ORDER BY r.registration_date DESC
```
* **Teacher Question:** *"Explain how you display event information in the student's My Events page."*
* **Answer:** *"We use an `INNER JOIN`. The `registrations` table only contains IDs (`user_id` and `event_id`). To show the event title, date, time, and venue, we join the `registrations` table with the `events` table where `registrations.event_id = events.id`, filtering by the logged-in student's `user_id`."*

---

## 5. Top 10 Most Common Viva Questions & Model Answers

### Q1: Why did you build this project using pure PHP and MySQL instead of frameworks like React, Node.js, or Laravel?
> **Answer:** *"Pure PHP and MySQL provide complete control over the underlying server architecture, HTTP request-response cycle, and database queries without hidden framework abstractions. This directly demonstrates core computer science and web programming competencies required by our BCA curriculum."*

### Q2: What is the difference between `GET` and `POST` methods in your project?
> **Answer:** *"We use `GET` for retrieval and filtering (such as browsing events or opening an event with `event_details.php?id=1`), because `GET` requests can be bookmarked and have data in the URL. We strictly use `POST` for sensitive operations like registration, login, profile updates, and creating events, because `POST` sends data in the HTTP request body and is not cached or exposed in browser history."*

### Q3: How do you prevent a student from registering for the same event twice?
> **Answer:** *"We enforce this at two distinct levels:
> 1. **Application Logic (PHP):** Before showing the register button, PHP executes `SELECT id FROM registrations WHERE user_id = ? AND event_id = ?`. If found, the button is disabled and replaced with an 'Already Registered' banner.
> 2. **Database Constraint (MySQL):** The table has `UNIQUE KEY (user_id, event_id)` which guarantees that even under race conditions, duplicate rows are rejected."*

### Q4: What happens if an admin deletes an event that students have already registered for?
> **Answer:** *"Because of the `FOREIGN KEY ... ON DELETE CASCADE` constraint on `registrations.event_id`, MySQL automatically cleans up all associated registration records without leaving orphaned data."*

### Q5: What is the difference between client-side validation and server-side validation in your system?
> **Answer:** *"Client-side validation runs in JavaScript in the user's browser for instant feedback (e.g., checking email format or minimum 6-character passwords). Server-side validation runs in PHP before inserting data into MySQL. Client-side validation can be bypassed by disabling JavaScript, so server-side validation is our primary line of defense."*

### Q6: How does the live search work on the Events page?
> **Answer:** *"In `assets/js/script.js`, we attach an `input` event listener to the search input. As the user types, JavaScript loops through all event cards, compares the title text, and hides or reveals matching cards using CSS `display: flex` / `display: none` in real time without refreshing the page."*

### Q7: How are the statistics on the Admin Dashboard calculated?
> **Answer:** *"They are 100% dynamic. We run aggregate queries: `SELECT COUNT(*) AS total FROM users` for students, `SELECT COUNT(*) FROM events` for events, and `SELECT COUNT(*) FROM registrations` for registrations. They update instantly as users register."*

### Q8: What is the difference between an Admin and a Student account?
> **Answer:** *"Students are stored in the `users` table and can only register for events and manage their personal profile. Admins are stored in a dedicated `admins` table with separate credentials and access restricted routes (`admin/dashboard.php`, `admin/events.php`, `admin/students.php`) guarded by `require_admin_login()`."*

### Q9: What is `htmlspecialchars()` and why did you use it?
> **Answer:** *"It converts special HTML characters like `<` and `>` into HTML entities (`&lt;`, `&gt;`). We use it whenever echoing user-generated content to the browser to prevent **Cross-Site Scripting (XSS)** attacks."*

### Q10: How does your UI achieve a modern look without Tailwind or Bootstrap?
> **Answer:** *"We designed a custom CSS stylesheet inspired by modern Figma UI designs. It uses CSS custom properties (`:root`), Google Fonts (`Outfit` and `Inter`), dark mode backgrounds with radial glow gradients, glassmorphism card blur (`backdrop-filter: blur(16px)`), and CSS Grid/Flexbox layouts."*

---

## 6. How to Run the Project on WAMPServer (Quick Guide for Viva)

1. **Start WAMPServer:**
   - Launch WAMPServer from your desktop/start menu. Wait until the WAMP tray icon turns **Green**.
2. **Database Verification:**
   - Open phpMyAdmin in browser: `http://localhost/phpmyadmin/`
   - Username: `root`, Password: *(leave blank)*.
   - If database is not created, click **Import** -> Choose file `database/campus_connect.sql` -> Click **Import**.
3. **Open the Website:**
   - Public Website: `http://localhost/campus_connect/`
   - Admin Panel: `http://localhost/campus_connect/admin/login.php`
4. **Test Accounts:**
   - **Student Demo:** Email: `rahul.sharma@college.edu` | Password: `student123`
   - **Admin Demo:** Username: `admin` | Password: `admin123`
