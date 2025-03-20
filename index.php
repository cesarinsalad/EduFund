<?php
// Iniciar sesión
session_start();

// Incluir la configuración
require_once 'config/db.php';
require_once 'config/config.php';

// Incluir header
include 'includes/header.php';

// Cargar la página principal
include 'views/home.php';

// Incluir footer
include 'includes/footer.php';
?>