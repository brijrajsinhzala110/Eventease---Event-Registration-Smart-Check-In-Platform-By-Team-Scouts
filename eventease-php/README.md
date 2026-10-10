# EventEase (PHP Edition)

A clean, maintainable PHP and MySQL application for College Event Registration & Check-In.

## Tech Stack
- PHP 8+
- MySQL / MariaDB (XAMPP compatible)
- HTML, Vanilla JavaScript
- Tailwind CSS (via CDN)
- JS libraries: `qrcode.js` (generation) & `html5-qrcode` (scanning)

## Setup Instructions

1. **Database Setup:**
   - Open phpMyAdmin in XAMPP (usually `http://localhost/phpmyadmin`).
   - Create a new database named `eventease`.
   - Import the `database/eventease.sql` file into this database. This will create the necessary tables and seed default users.

2. **Configuration:**
   - Copy `config.example.php` to `config/database.php`.
   - Open `config/database.php` and verify the database credentials.
     - **Default XAMPP settings**: Database Name: `eventease`, Username: `root`, Password: `(leave blank)`.
   - *Note: `config/database.php` should never be committed to a public repository containing real production passwords.*

3. **Running the Application:**
   - Move or clone this `eventease-php` directory into your XAMPP `htdocs` folder (e.g., `C:\xampp\htdocs\eventease`).
   - Start the Apache and MySQL services in the XAMPP Control Panel.
   - Open your web browser and go to `http://localhost/eventease`.

## Demo Logins

The database includes the following seed accounts (Password for all is `password`):

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@college.edu | password |
| **Organizer** | organizer@college.edu | password |
| **Student** | alice@student.edu | password |

## Folder Structure Highlights
- `/config`: Database connection configuration.
- `/includes`: Reusable components (auth checks, functions, header/navbar, CSRF protection).
- `/database`: Contains the `eventease.sql` schema and seed file.
- `/student`: Pages for students to browse events, register, and view QR tickets.
- `/organizer`: Pages for organizers to create events, manage registrations, and scan tickets.
- `/admin`: Dashboard for administrators to manage users and events.
- `/api`: Contains the `checkin.php` endpoint used by the QR scanner.
