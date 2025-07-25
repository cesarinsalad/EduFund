-- Creación de tabla de usuarios.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'donor', 'admin') NOT NULL DEFAULT 'donor',
    status ENUM('pending', 'active', 'blocked') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para información adicional de estudiantes.
CREATE TABLE student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    institution VARCHAR(100) NOT NULL,
    educational_level VARCHAR(50) NOT NULL,
    document_number VARCHAR(50) NOT NULL,
    bio TEXT,
    profile_picture VARCHAR(255),
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verification_documents VARCHAR(255),
    verification_notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla para información adicional de donantes.
CREATE TABLE donor_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100),
    display_name VARCHAR(100),
    donation_privacy BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla que almacena datos de las campañas.

CREATE TABLE campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    goal_amount DECIMAL(10,2) NOT NULL,
    current_amount DECIMAL(10,2) DEFAULT 0.00,
    campaign_image VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending', 'active', 'completed', 'rejected') DEFAULT 'pending',
    category VARCHAR(50) NOT NULL,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabla que almacena información de las donaciones realizadas a las campañas.

CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    donor_id INT, -- Acepta datos NULL para permitir donaciones anónimas.
    amount DECIMAL(10,2) NOT NULL,
    payment_id VARCHAR(255) NOT NULL,
    payment_status ENUM('pending', 'completed', 'refunded', 'failed') DEFAULT 'pending',
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    anonymous BOOLEAN DEFAULT FALSE,
    message TEXT,
    donor_name VARCHAR(100),
    donor_email VARCHAR(100),
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE SET NULL
);