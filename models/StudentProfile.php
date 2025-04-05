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
        
        $stmt->bindParam(':user_id', $this->user_id);
        
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

    /**
    * Cuenta las verificaciones pendientes
    * 
    * NOTA: En esta fase devuelve un valor de muestra.
    * La implementación real se hará en la Fase 4.
    */
    
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
public function updateVerificationStatus($user_id, $status) {
    $query = "UPDATE student_profiles SET verification_status = ? WHERE user_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $status, $user_id);
    
    $result = $stmt->execute();
    
    // Si la actualización fue exitosa, sincronizar con users
    if ($result) {
        require_once 'models/UserUtility.php';
        $utility = new UserUtility($this->conn);
        $utility->syncVerificationStatus($user_id, 'student_profiles', $status);
    }
    
    return $result;
}
}
?>