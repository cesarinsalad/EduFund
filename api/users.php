<?php
// api/users.php
session_start();
require_once '../config/db.php';
require_once '../models/User.php';
require_once '../models/StudentProfile.php';

// Crear instancia de user
$user = new User($conn);
$student_profile = new StudentProfile($conn);

// Verificar qué acción se está solicitando
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Función para sanitizar strings (reemplazo para FILTER_SANITIZE_STRING)
function sanitize_string($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Procesar la acción solicitada
switch ($action) {
    case 'register':
        handleRegister($user, $student_profile);
        break;
    case 'login':
        handleLogin($user);
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

// Función para manejar el registro de usuarios
function handleRegister($user, $student_profile) {
    // Uso de métodos modernos de sanitización
    $name = sanitize_string($_POST['name'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = sanitize_string($_POST['password'] ?? '');
    $user_type = sanitize_string($_POST['user_type'] ?? '');
    
    if (empty($name) || empty($email) || empty($password) || empty($user_type)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        return;
    }
    
    // Registrar usuario
    $result = $user->register($name, $email, $password, $user_type);
    
    // Si es estudiante y registro exitoso, crear perfil
    if ($result['success'] && $user_type === 'student') {
        $institution = sanitize_string($_POST['institution'] ?? '');
        $study_program = sanitize_string($_POST['study_program'] ?? '');
        $student_id = sanitize_string($_POST['student_id'] ?? '');
        
        $profile_result = $student_profile->create(
            $result['user_id'],
            $institution,
            $study_program,
            $student_id
        );
        
        // Si hay error en la creación del perfil, actualizar mensaje
        if (!$profile_result['success']) {
            $result['message'] = 'Usuario registrado pero hubo un problema con el perfil de estudiante';
        }
    }
    
    echo json_encode($result);
}

// Función para manejar el inicio de sesión
function handleLogin($user) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = sanitize_string($_POST['password'] ?? '');
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Correo y contraseña son obligatorios']);
        return;
    }
    
    $result = $user->login($email, $password);
    
    if ($result['success']) {
        // Guardar datos de usuario en sesión
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['user_name'] = $result['user']['name'];
        $_SESSION['user_email'] = $result['user']['email'];
        $_SESSION['user_type'] = $result['user']['user_type'];
    }
    
    echo json_encode($result);
}

// Función para manejar el cierre de sesión
function handleLogout() {
    // Destruir la sesión
    session_unset();
    session_destroy();
    
    echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente']);
}
?>