<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/StudentProfile.php';
require_once __DIR__ . '/../models/DonorProfile.php';

class AuthController {
    private $db;
    private $userModel; // Añadir propiedad para el modelo User

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db); // Instanciar el modelo User aquí
    }

    // Registrar un nuevo usuario
    public function register($data) {
        $response = [
            'success' => false,
            'message' => '',
            'errors' => [],
            'user_id' => null
        ];

        // --- 1. Validación de Datos Comunes ---
        $requiredFields = ['username', 'email', 'password', 'role'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $response['errors'][$field] = 'El campo ' . $field . ' es obligatorio.';
            }
        }
        if (!empty($response['errors'])) {
            $response['message'] = 'Por favor, complete todos los campos obligatorios.';
            return $response;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = 'El formato del email es inválido.';
        }
        if (strlen($data['password']) < 8) {
            $response['errors']['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        $allowed_roles = ['student', 'donor']; // Considera admin si es posible registrarlo así
        if (!in_array($data['role'], $allowed_roles)) {
             $response['errors']['role'] = 'Rol de usuario inválido.';
        }

        // --- 2. Validación de Datos Específicos del Rol (si aplica) ---
        if ($data['role'] === 'student') {
            $requiredStudentFields = ['full_name', 'institution', 'educational_level', 'document_number'];
            foreach ($requiredStudentFields as $field) {
                if (empty($data[$field])) {
                    $response['errors'][$field] = 'El campo ' . $field . ' es obligatorio para estudiantes.';
                }
            }
        } elseif ($data['role'] === 'donor') {
             // Validaciones específicas para donantes si las hubiera
             // Por ejemplo, asegurar que full_name o display_name estén presentes si son requeridos
             if (empty($data['full_name']) && empty($data['display_name'])) {
                 // Opcional: si al menos uno es requerido
                 //$response['errors']['donor_name'] = 'Debe proporcionar un nombre completo o un nombre a mostrar.';
             }
        }

        if (!empty($response['errors'])) {
            $response['message'] = 'Error de validación. Por favor, revise los campos.';
            return $response;
        }

        // --- 3. Intentar Registrar Usuario Base ---
        // Usamos el método 'register' del modelo User, que maneja la existencia y el hash
        $registrationResult = $this->userModel->register(
            $data['username'],
            $data['email'],
            $data['password'],
            $data['role']
            // 'status' se maneja en el modelo User::register ahora
        );

        if (!$registrationResult['success']) {
            $response['message'] = $registrationResult['message']; // Usar mensaje del modelo
            // Podríamos mapear errores específicos del modelo a 'errors' si fuera necesario
            if (strpos($registrationResult['message'], 'ya está registrado') !== false) {
                 $response['errors']['email'] = 'El email o nombre de usuario ya está registrado.';
                 $response['message'] = 'El email o nombre de usuario ya está registrado.';
            }
            return $response;
        }

        // --- 4. Usuario Base Creado Exitosamente ---
        $user_id = $registrationResult['user_id'];
        $response['user_id'] = $user_id;
        $response['message'] = 'Usuario base registrado correctamente.'; // Mensaje inicial

        // --- 5. Crear Perfil Específico del Rol ---
        $profileCreated = false;
        $profileMessage = '';

        try {
            if ($data['role'] == 'student') {
                $student = new StudentProfile($this->db);
                $student->user_id = $user_id;
                $student->full_name = $data['full_name'];
                $student->institution = $data['institution'];
                $student->educational_level = $data['educational_level'];
                $student->document_number = $data['document_number'];
                // Usar null coalescing operator para defaults más limpio
                $student->bio = $data['bio'] ?? '';
                $student->profile_picture = $data['profile_picture'] ?? ''; // Considerar manejo de subida de archivos aquí o aparte
                $student->verification_documents = $data['verification_documents'] ?? ''; // Considerar manejo de subida de archivos

                if ($student->create()) {
                    $profileCreated = true;
                    $profileMessage = ' Perfil de estudiante creado.';
                } else {
                    $profileMessage = ' Error al crear el perfil de estudiante.';
                    // Considerar loggear el error específico de $student->create() si lo devuelve
                }

            } elseif ($data['role'] == 'donor') {
                $donor = new DonorProfile($this->db);
                $donor->user_id = $user_id;
                $donor->full_name = $data['full_name'] ?? '';
                 // Usar username como fallback para display_name si no se proporciona
                $donor->display_name = $data['display_name'] ?? $data['username'];
                // Asumiendo que DonorProfile también puede tener profile_picture
                $donor->profile_picture = $data['profile_picture'] ?? '';

                if ($donor->create()) {
                    $profileCreated = true;
                    $profileMessage = ' Perfil de donante creado.';
                } else {
                    $profileMessage = ' Error al crear el perfil de donante.';
                }
            } else {
                // Si hay otros roles sin perfil específico o admin
                $profileCreated = true; // No hay perfil que crear, así que se considera éxito
                $profileMessage = ' Rol sin perfil adicional.';
            }

            if ($profileCreated) {
                $response['success'] = true;
                $response['message'] .= $profileMessage;
                // Aquí podrías enviar un email de bienvenida o de activación si status es 'pending'
            } else {
                // El usuario base se creó, pero el perfil falló. ¿Qué hacer?
                // Opción 1: Dejar como está y reportar error (actual)
                $response['success'] = false; // Marcar como no exitoso si el perfil es crucial
                $response['message'] .= $profileMessage;
                // Opción 2: Intentar eliminar el usuario base (rollback manual) - más complejo
                // $this->userModel->deleteById($user_id);
                // $response['message'] = 'Error creando perfil. Se canceló el registro.';
            }

        } catch (Exception $e) {
             $response['success'] = false;
             $response['message'] = 'Error inesperado durante la creación del perfil: ' . $e->getMessage();
             // Considerar loggear el error $e
        }

        return $response;
    }

    // Iniciar sesión
    public function login($email, $password) {
        $response = [
            'success' => false,
            'message' => '',
            'user' => null
        ];

        // Verificar si se proporcionaron credenciales
        if (empty($email) || empty($password)) {
            $response['message'] = 'El email y la contraseña son obligatorios';
            return $response;
        }

        // Llamar al método login del modelo User
        $loginResult = $this->userModel->login($email, $password);

        if ($loginResult['success']) {
            $user_data = $loginResult['user']; // Obtener datos del array devuelto

            // Verificar si la cuenta está activa ANTES de iniciar sesión
            if ($user_data['status'] != 'active') {
                $response['message'] = 'Tu cuenta está pendiente de activación o ha sido bloqueada.';
                // No establecemos success = true ni iniciamos sesión
                return $response;
            }

            // Iniciar sesión (Guardar en $_SESSION)
            $_SESSION['user_id'] = (int)$user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['role'] = $user_data['role'];

            // Inicializar datos de sesión adicionales
            $_SESSION['full_name'] = null; // Por defecto null
            $_SESSION['profile_picture'] = 'assets/img/default-avatar.png'; // Default

            $response['success'] = true;
            $response['message'] = 'Inicio de sesión exitoso';
            $response['user'] = [ // Devolver info básica al frontend si es necesario
                'id' => $user_data['id'],
                'username' => $user_data['username'],
                'role' => $user_data['role'],
                'profile' => null // Inicializar perfil
            ];

            // Obtener datos adicionales y guardarlos en sesión y respuesta
            try {
                if ($user_data['role'] == 'student') {
                    $student = new StudentProfile($this->db);
                    $student->user_id = $user_data['id'];
                    if ($student->readByUserId() && $student->user_id) { // Verificar que encontró algo
                        if (!empty($student->profile_picture)) {
                            $_SESSION['profile_picture'] = $student->profile_picture;
                        }
                        $_SESSION['full_name'] = $student->full_name;
                        // Guardar otros datos relevantes si es necesario
                        // $_SESSION['verification_status'] = $student->verification_status;

                        $response['user']['profile'] = [
                            'full_name' => $student->full_name,
                            'institution' => $student->institution,
                            //'verification_status' => $student->verification_status, // Descomentar si se necesita devolver
                            'profile_picture' => $_SESSION['profile_picture'] // Usar el valor final de sesión
                        ];
                    }
                } elseif ($user_data['role'] == 'donor') {
                    $donor = new DonorProfile($this->db);
                    $donor->user_id = $user_data['id'];
                    if ($donor->readByUserId() && $donor->user_id) {
                        // Asumiendo que DonorProfile puede tener 'profile_picture'
                        if (property_exists($donor, 'profile_picture') && !empty($donor->profile_picture)) {
                            $_SESSION['profile_picture'] = $donor->profile_picture;
                        }
                        $_SESSION['full_name'] = $donor->full_name; // Asumiendo que existe
                        // Guardar otros datos relevantes si es necesario
                        // $_SESSION['display_name'] = $donor->display_name;

                         $response['user']['profile'] = [
                            'full_name' => $donor->full_name,
                            'display_name' => $donor->display_name ?? null, // Usar null coalescing
                            'profile_picture' => $_SESSION['profile_picture']
                        ];
                    }
                } elseif ($user_data['role'] == 'admin') {
                    // Los admins pueden no tener perfil, usar un avatar específico
                    $_SESSION['profile_picture'] = 'assets/img/admin-avatar.png'; // O dejar el default
                    $_SESSION['full_name'] = 'Administrador'; // O buscar en tabla de admins si existe
                     $response['user']['profile'] = [
                         'full_name' => $_SESSION['full_name'],
                         'profile_picture' => $_SESSION['profile_picture']
                     ];
                }
            } catch (Exception $e) {
                // Error al cargar perfil, pero el login fue exitoso
                // Puedes loggear el error $e->getMessage()
                $response['message'] .= ' (Advertencia: no se pudo cargar el perfil completo)';
                // La sesión básica ya está iniciada, así que mantenemos success = true
            }

        } else {
            // Login fallido según el modelo
            $response['message'] = 'El correo electrónico o la contraseña son incorrectos.';
        }

        return $response;
    }

    // Cerrar sesión
    public function logout() {
        // 1. Asegurarse de que la sesión esté iniciada para poder destruirla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Destruir todas las variables de sesión
        $_SESSION = array(); // Opcional: limpiar el array $_SESSION

        // 3. Borrar la cookie de sesión si se usa
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 4. Destruir la sesión
        session_destroy();

        // 5. Redirigir (ANTES de cualquier return)
        // Ajusta la ruta según sea necesario
        header("Location: ../index.php"); // O a /login.php
        exit(); // Detener ejecución después de redirigir

        // Ya no se necesita devolver nada porque la ejecución termina con exit()
        /*
        return [
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ];
        */
    }
}
?>