<?php
require_once 'controllers/AuthController.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    
    // Procesar documentos subidos
    $verification_documents = '';
    
    if (isset($_FILES['verification_documents']) && $_FILES['verification_documents']['error'] == 0) {
        $upload_dir = 'uploads/documents/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['verification_documents']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['verification_documents']['tmp_name'], $target_file)) {
            $verification_documents = $target_file;
        }
    }
    
    // Agregar archivos subidos al array de datos
    $_POST['verification_documents'] = $verification_documents;
    
    // Registrar usuario
    $result = $auth->register($_POST);
    
    if ($result['success']) {
        // Redirigir al login con mensaje de éxito
        $_SESSION['registration_success'] = true;
        $_SESSION['message'] = 'Te has registrado correctamente. Ahora puedes iniciar sesión.';
        header('Location: index.php?page=login');
        exit;
    } else {
        // Mostrar error
        $error_message = $result['message'];
        if (!empty($result['errors'])) {
            $error_message .= '<ul>';
            foreach ($result['errors'] as $field => $error) {
                $error_message .= '<li>' . $error . '</li>';
            }
            $error_message .= '</ul>';
        }
        include 'views/auth/register.php';
    }
} else {
    // Si alguien intenta acceder directamente, redirigir al formulario de registro
    header('Location: index.php?page=register');
    exit;
}
?>