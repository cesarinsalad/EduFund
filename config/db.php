<?php
// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Usuario predeterminado en Laragon
define('DB_PASS', '');     // Contraseña vacía por defecto en Laragon
define('DB_NAME', 'edufund');

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");
?>