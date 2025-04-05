<?php
class User {
    private $conn;
    private $table_name = "users"; // Definir nombre de tabla

    // Propiedades del objeto (opcional, pero útil para claridad)
    public $id;
    public $username;
    public $email;
    public $password; // Solo para recibirla, no almacenar en el objeto
    public $role;
    public $status;
    public $profile_image; // Quitado si no está en la tabla users
    // public $verification_status; // Quitado si no está en la tabla users
    public $created_at;
    public $updated_at;


    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Registro de usuario (mejorado)
    public function register($username, $email, $password, $role = 'donor', $status = 'pending') {
        // 1. Verificar si el correo O el nombre de usuario ya existen
        if ($this->usernameOrEmailExists($username, $email)) {
            return ["success" => false, "message" => "El nombre de usuario o el correo electrónico ya están registrados"];
        }

        // 2. Hash de contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Preparar consulta INSERT (añadir status y campos de timestamp)
        $query = "INSERT INTO " . $this->table_name . "
                    (username, email, password, role, status, created_at, updated_at)
                  VALUES
                    (:username, :email, :password, :role, :status, NOW(), NOW())";

        $stmt = $this->conn->prepare($query);

        // 4. Limpiar datos (aunque bindParam/Value usualmente maneja la mayoría)
        $username = htmlspecialchars(strip_tags($username));
        $email = htmlspecialchars(strip_tags($email));
        $role = htmlspecialchars(strip_tags($role));
        $status = htmlspecialchars(strip_tags($status));

        // 5. Vincular parámetros
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status); // Vincular status

        // 6. Ejecutar y devolver resultado
        if ($stmt->execute()) {
            return [
                "success" => true,
                "user_id" => $this->conn->lastInsertId(),
                "message" => "Registro de usuario base exitoso"
            ];
        } else {
            // Loggear el error real para depuración interna
            // error_log("Error DB Register: " . implode(", ", $stmt->errorInfo()));
            return [
                "success" => false,
                // Mensaje más genérico para el usuario
                "message" => "Error al registrar el usuario en la base de datos."
                // "message" => "Error al registrar: " . implode(", ", $stmt->errorInfo()) // Para debug
            ];
        }
    }

    // Inicio de sesión (mejorado para incluir status)
    public function login($email, $password) {
        // Seleccionar campos necesarios, incluyendo 'status'
        $query = "SELECT id, username, email, password, role, status
                  FROM " . $this->table_name . "
                  WHERE email = :email
                  LIMIT 1"; // Añadir LIMIT 1 por si acaso

        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña
            if (password_verify($password, $user['password'])) {
                // Contraseña correcta
                unset($user['password']); // No devolver el hash
                return [
                    "success" => true,
                    "user" => $user, // Devolver todos los datos recuperados (id, username, email, role, status)
                    "message" => "Credenciales válidas"
                ];
            }
        }

        // Si el email no existe o la contraseña es incorrecta
        return [
            "success" => false,
            "message" => "El correo electrónico o la contraseña son incorrectos."
        ];
    }

    // Verificar si el email O username ya existen
    public function usernameOrEmailExists($username, $email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email OR username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $username = htmlspecialchars(strip_tags($username));

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Obtener usuario por ID (revisar campos seleccionados)
    public function getById($user_id) {
        // Seleccionar solo campos REALES de la tabla 'users'
        $query = "SELECT id, username, email, role, status, created_at, updated_at
                  FROM " . $this->table_name . "
                  WHERE id = :user_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    /**
    * Cuenta el número total de usuarios (Implementación real)
    */
    public function count() {
         try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['total'];
        } catch (PDOException $e) {
            // Log error $e->getMessage()
            return 0; // Devolver 0 en caso de error
        }
    }

    // (Opcional) Método para eliminar usuario si falla la creación del perfil
    public function deleteById($user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0; // Devuelve true si se eliminó 1 fila
        }
        return false;
    }

    // Añadir este método a la clase User existente

public function updateStatus($user_id, $status) {
    $query = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $status, $user_id);
    
    $result = $stmt->execute();
    
    // Si la actualización fue exitosa y es un estudiante, sincronizar
    if ($result) {
        $query = "SELECT role FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && $user['role'] == 'student') {
            require_once 'models/UserUtility.php';
            $utility = new UserUtility($this->conn);
            $utility->syncVerificationStatus($user_id, 'users', $status);
        }
    }
    
    return $result;
}
}
?>