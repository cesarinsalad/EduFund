<?php
require_once 'controllers/AuthController.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    
    // Obtener datos del formulario
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Iniciar sesión
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        // Redirigir según el rol del usuario
        switch ($_SESSION['role']) {
            case 'admin':
                header('Location: index.php?page=admin_dashboard');
                break;
            case 'student':
                header('Location: index.php?page=student_dashboard');
                break;
            case 'donor':
                header('Location: index.php?page=donor_dashboard');
                break;
            default:
                header('Location: index.php');
                break;
        }
        exit;
    } else {
        // Mostrar error
        $error_message = $result['message'];
        include 'views/auth/login.php';
    }
} else {
    // Si alguien intenta acceder directamente, redirigir al formulario de login
    header('Location: index.php?page=login');
    exit;
}
?>