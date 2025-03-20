<?php
// Configuración general del sitio
define('SITE_NAME', 'EduFund');
define('SITE_URL', 'http://edufund.test'); // URL del sitio local

// Configuración de correo electrónico (para futuras notificaciones)
define('EMAIL_FROM', 'noreply@edufund.test');
define('EMAIL_NAME', 'EduFund');

// Configuración de la aplicación
define('DEBUG_MODE', true); // Establecer en false para producción

// Función para mostrar errores en modo de depuración
function debug($data) {
    if (DEBUG_MODE) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

// Configuración de rutas
define('ROOT_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', ROOT_PATH . 'uploads/');

// Configuración para prevenir acceso directo a los archivos
defined('SECURE_ACCESS') or define('SECURE_ACCESS', true);
?>