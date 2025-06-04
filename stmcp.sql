CREATE TABLE roles (
  role_id INT PRIMARY KEY AUTO_INCREMENT,
  role VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (role) VALUES
('Super'),
('Admin'),
('Moderator'),
('Official Member'),
('Aspirant');

CREATE TABLE chapters (
  chapter_id INT PRIMARY KEY AUTO_INCREMENT,
  chapter_name VARCHAR(255) NOT NULL UNIQUE
);

INSERT INTO chapters (chapter_name) VALUES
('Quezon City'),
('Mandasan (Mandaluyong & San Juan)'),
('Pasay'),
('Valenzuela'),
('Manila'),
('TagMak (Taguig & Makati)'),
('San Jose Del Monte, Bulacan'),
('Pampanga'),
('Pasig'),
('Santa Maria, Bulacan'),
('Rizal (Taytay, Cainta, Antipolo, Angono)'),
('North Caloocan'),
('Metro South (Taguig)'),
('Laguna'),
('Cavite'),
('Pangasinan'),
('Ilocos (Sur, Norte)'),
('Malabon'),
('Montalban (Rizal)'),
('San Mateo (Rizal)');

CREATE TABLE admins (
  admin_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  chapter_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (chapter_id) REFERENCES chapters(chapter_id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT
);