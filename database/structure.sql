-- Estructura de la tabla users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('student','donor','admin') NOT NULL DEFAULT 'donor',
  `profile_image` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estructura de la tabla student_profiles
CREATE TABLE `student_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `institution` varchar(200) DEFAULT NULL,
  `study_program` varchar(200) DEFAULT NULL,
  `student_id` varchar(100) DEFAULT NULL,
  `verification_document` varchar(255) DEFAULT NULL,
  `bio` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `student_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar un administrador por defecto
INSERT INTO `users` (`name`, `email`, `password`, `user_type`, `status`) VALUES
('Admin', 'admin@edufund.com', '$2y$10$ZQvFJMLVFLDGkIN9x7DnW.JdoJJDn092.SZGcJyy16nGKE78IcC4e', 'admin', 'active');
-- Contraseña: admin123