<?php
// models/StudentProfile.php
class StudentProfile {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Crear perfil de estudiante
    public function create($user_id, $institution, $study_program, $student_id, $verification_document = null, $bio = null) {
        $query = "INSERT INTO student_profiles (user_id, institution, study_program, student_id, verification_document, bio) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $institution, $study_program, $student_id, $verification_document, $bio);
        
        if ($stmt->execute()) {
            // Actualizar el tipo de usuario a estudiante
            $user_query = "UPDATE users SET user_type = 'student', verification_status = 'pending' WHERE id = ?";
            $user_stmt = $this->conn->prepare($user_query);
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            
            return [
                "success" => true,
                "profile_id" => $this->conn->insert_id,
                "message" => "Perfil de estudiante creado exitosamente"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Error al crear el perfil: " . $stmt->error
            ];
        }
    }
    
    // Obtener perfil de estudiante por user_id
    public function getByUserId($user_id) {
        $query = "SELECT sp.*, u.verification_status 
                  FROM student_profiles sp
                  JOIN users u ON sp.user_id = u.id 
                  WHERE sp.user_id = ?";
                  
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