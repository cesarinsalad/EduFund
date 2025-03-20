<?php
// models/User.php
class User {
    private $conn;
    
    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Registro de usuario
    public function register($name, $email, $password, $user_type = 'donor') {
        // Verificar si el correo ya existe
        if ($this->emailExists($email)) {
            return ["success" => false, "message" => "El correo electrónico ya está registrado"];
        }
        
        // Hash de contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar consulta
        $query = "INSERT INTO users (name, email, password, user_type) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $user_type);
        
        if ($stmt->execute()) {
            return [
                "success" => true,
                "user_id" => $this->conn->insert_id,
                "message" => "Registro exitoso"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Error al registrar: " . $stmt->error
            ];
        }
    }
    
    // Inicio de sesión
    public function login($email, $password) {
        $query = "SELECT id, name, email, password, user_type 
                  FROM users 
                  WHERE email = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Eliminar la contraseña del array antes de devolverlo
                unset($user['password']);
                return [
                    "success" => true,
                    "user" => $user,
                    "message" => "Inicio de sesión exitoso"
                ];
            }
        }
        
        return [
            "success" => false,
            "message" => "Correo o contraseña incorrectos"
        ];
    }
    
    // Verificar si el email ya existe
    private function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Obtener perfil de usuario por ID
    public function getById($user_id) {
        $query = "SELECT id, name, email, user_type, profile_image, 
                  verification_status, created_at 
                  FROM users 
                  WHERE id = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
?>