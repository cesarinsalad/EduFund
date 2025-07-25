<?php
class StudentProfile {
    private $conn;
    private $table = 'student_profiles';
    
    // Propiedades
    public $id;
    public $user_id;
    public $full_name;
    public $institution;
    public $educational_level;
    public $document_number;
    public $bio;
    public $profile_picture;
    public $verification_status;
    public $verification_documents;
    public $verification_notes;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Crear perfil de estudiante
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id = :user_id, 
                      full_name = :full_name, 
                      institution = :institution, 
                      educational_level = :educational_level,
                      document_number = :document_number,
                      bio = :bio,
                      profile_picture = :profile_picture,
                      verification_documents = :verification_documents";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->user_id = htmlspecialchars(strip_tags((string)$this->user_id));
        $this->full_name = htmlspecialchars(strip_tags((string)$this->full_name));
        $this->institution = htmlspecialchars(strip_tags((string)$this->institution));
        $this->educational_level = htmlspecialchars(strip_tags((string)$this->educational_level));
        $this->document_number = htmlspecialchars(strip_tags((string)$this->document_number));
        $this->bio = htmlspecialchars(strip_tags((string)$this->bio));
        
        // Vincular los parámetros
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':institution', $this->institution);
        $stmt->bindParam(':educational_level', $this->educational_level);
        $stmt->bindParam(':document_number', $this->document_number);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':profile_picture', $this->profile_picture);
        $stmt->bindParam(':verification_documents', $this->verification_documents);
        
        // Ejecutar query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Obtener perfil por user_id
    public function readByUserId() {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->full_name = $row['full_name'];
            $this->institution = $row['institution'];
            $this->educational_level = $row['educational_level'];
            $this->document_number = $row['document_number'];
            $this->bio = $row['bio'];
            $this->profile_picture = $row['profile_picture'];
            $this->verification_status = $row['verification_status'];
            $this->verification_documents = $row['verification_documents'];
            $this->verification_notes = $row['verification_notes'];
            
            return true;
        }
        
        return false;
    }

    // Cuenta las verificaciones pendientes.
    public function countPendingVerifications() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE verification_status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

/**
* Obtiene la lista de estudiantes pendientes de verificación
*/
public function getPendingVerifications() {
    // Modificar la consulta para incluir u.created_at
    $query = "SELECT sp.*, u.username, u.created_at 
              FROM " . $this->table . " sp
              JOIN users u ON sp.user_id = u.id 
              WHERE sp.verification_status = 'pending'
              ORDER BY sp.id DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateVerificationStatus($profile_id, $status, $notes, $admin_id = null) {
    // Validar el estado
    if (!in_array($status, ['verified', 'rejected', 'pending'])) {
        return false;
    }
    
    try {
        // Actualizar el perfil del estudiante
        $query = "UPDATE " . $this->table . " 
                  SET verification_status = :status, 
                      verification_notes = :notes
                  WHERE id = :profile_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':profile_id', $profile_id);
        
        if (!$stmt->execute()) {
            return false;
        }
        
        // Obtener el user_id asociado a este perfil
        $query = "SELECT user_id FROM " . $this->table . " WHERE id = :profile_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':profile_id', $profile_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Determinar estado de usuario según verificación
            $user_status = ($status == 'verified') ? 'active' : (($status == 'rejected') ? 'blocked' : 'pending');
            
            // Actualizar el estado del usuario
            $query = "UPDATE users SET status = :user_status WHERE id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_status', $user_status);
            $stmt->bindParam(':user_id', $row['user_id']);
            $stmt->execute();
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error en verificación: " . $e->getMessage());
        return false;
    }
}

public function getVerificationsReport($date_start = '', $date_end = '') {
    try {
        $sql = "SELECT sp.id as ID, 
                       sp.full_name as 'Nombre Completo', 
                       sp.institution as 'Institución', 
                       sp.educational_level as 'Nivel Educativo', 
                       sp.verification_status as 'Estado de Verificación', 
                       sp.verification_notes as 'Notas de Verificación',  
                       u.email as 'Correo Electrónico',
                       u.created_at as 'Fecha de Registro'
                FROM student_profiles sp
                JOIN users u ON sp.user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        // Aplicar filtros de fecha si se proporcionan
        if (!empty($date_start)) {
            $sql .= " AND DATE(u.created_at) >= ?";
            $params[] = $date_start;
        }
        
        if (!empty($date_end)) {
            $sql .= " AND DATE(u.created_at) <= ?";
            $params[] = $date_end;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        $verifications = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verifications[] = $row;
        }
        
        return $verifications;
    } catch (PDOException $e) {
        error_log("Error obteniendo datos de verificaciones para reporte: " . $e->getMessage());
        return [];
    }
}

}
?>