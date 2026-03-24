CREATE TABLE IF NOT EXISTS buildings (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(50)  DEFAULT NULL,
  description  TEXT         DEFAULT NULL,
  stroke_color VARCHAR(50)  DEFAULT NULL,
  fill_color   VARCHAR(50)  DEFAULT NULL,
  date_created DATETIME     DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS building_points (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  building_id INT NOT NULL,
  lat         DECIMAL(20,15) DEFAULT NULL,
  lng         DECIMAL(20,15) DEFAULT NULL,
  point_order INT NOT NULL,
  FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS rooms (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  building_id INT NOT NULL,
  floor       INT NOT NULL,
  name        VARCHAR(255) NOT NULL,
  details     TEXT NOT NULL,
  type        VARCHAR(100) DEFAULT NULL,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS announcements (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(255) NOT NULL,
  message    TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name     VARCHAR(150),
  role          VARCHAR(50)  DEFAULT 'admin',
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
  last_login    DATETIME
);

CREATE TABLE IF NOT EXISTS activity_logs (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  admin_id     INT NOT NULL,
  action       VARCHAR(50) NOT NULL,
  target_table VARCHAR(50),
  target_id    INT,
  details      TEXT,
  performed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES admin_users(id)
);

-- Default admin account (password: admin123)
INSERT IGNORE INTO admin_users (id, username, password_hash, full_name)
VALUES (1, 'admin', SHA2('admin123', 256), 'Administrator');
