<?php
class DonorProfile {
    private $conn;
    private $table = 'donor_profiles';
    
    // Propiedades
    public $id;
    public $user_id;
    public $full_name;
    public $display_name;
    public $donation_privacy;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Crear perfil de donante
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id = :user_id, 
                      full_name = :full_name, 
                      display_name = :display_name";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->display_name = htmlspecialchars(strip_tags($this->display_name));
        
        // Vincular los parámetros
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':display_name', $this->display_name);
        
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
            $this->display_name = $row['display_name'];
            $this->donation_privacy = $row['donation_privacy'];
            
            return true;
        }
        
        return false;
    }
}
?>