CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    student_id VARCHAR(50),
    department VARCHAR(100),
    year INT,
    phone VARCHAR(20),
    role ENUM('STUDENT', 'ORGANIZER', 'ADMIN', 'STAFF') DEFAULT 'STUDENT',
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100),
    venue VARCHAR(255) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    capacity INT NOT NULL,
    registration_deadline DATETIME NOT NULL,
    image_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    status ENUM('REGISTERED', 'WAITLISTED', 'CANCELLED') DEFAULT 'REGISTERED',
    ticket_code VARCHAR(50) NOT NULL UNIQUE,
    checked_in_at DATETIME NULL,
    checked_in_by_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY user_event_unique (user_id, event_id)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed Data
INSERT INTO users (name, email, student_id, role, password_hash) VALUES 
('Admin User', 'admin@college.edu', NULL, 'ADMIN', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password
('Organizer User', 'organizer@college.edu', NULL, 'ORGANIZER', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password
('Alice Student', 'alice@student.edu', 'STU001', 'STUDENT', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password
INSERT INTO events (title, description, category, venue, starts_at, ends_at, capacity, registration_deadline) VALUES 
('Annual Tech Symposium', 'A deep dive into emerging technologies, featuring keynote speakers from top tech companies.', 'Technology', 'Main Hall', DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 12 DAY), 200, DATE_ADD(NOW(), INTERVAL 8 DAY)),
('Career Fair 2026', 'Connect with top employers offering internships and full-time positions. Bring your resume!', 'Career', 'Sports Complex', DATE_ADD(NOW(), INTERVAL 20 DAY), DATE_ADD(NOW(), INTERVAL 21 DAY), 500, DATE_ADD(NOW(), INTERVAL 19 DAY)),
('Photography Workshop', 'Learn the basics of DSLR photography, lighting, and composition.', 'Workshop', 'Art Studio 3', DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 5 DAY), 30, DATE_ADD(NOW(), INTERVAL 3 DAY)),
('Inter-College Hackathon', '48 hours of intense coding, networking, and building amazing projects.', 'Technology', 'Lab Complex', DATE_ADD(NOW(), INTERVAL 30 DAY), DATE_ADD(NOW(), INTERVAL 32 DAY), 100, DATE_ADD(NOW(), INTERVAL 25 DAY)),
('Guest Lecture: AI Ethics', 'A thought-provoking discussion on the future of AI and its ethical implications.', 'Seminar', 'Auditorium', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY), 150, DATE_ADD(NOW(), INTERVAL 1 DAY));
