-- // db.sql
-- إنشاء جدول clients
CREATE TABLE clients (
  client_code INT PRIMARY KEY,
  client_name VARCHAR(255),
  status VARCHAR(255) DEFAULT 'not modified',
  branch_code VARCHAR(255)
);

-- إنشاء جدول modifications
CREATE TABLE modifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_code INT,
  client_name VARCHAR(255),
  coordinates VARCHAR(255),
  notes TEXT,
  date_time DATETIME,
  FOREIGN KEY (client_code) REFERENCES clients(client_code)
);



-- إنشاء جدول users
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  role VARCHAR(255),
  branch VARCHAR(255),
  branch_code VARCHAR(255),
  sector ENUM('tp', 'bk', 'fr'),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  permission ENUM('له حق التعديل', 'ليس له حق التعديل')
);
