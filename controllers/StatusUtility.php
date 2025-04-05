<?php
require_once '../config/Database.php';

class UserUtility {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Sincroniza el estado de verificación entre las tablas users y student_profiles
     * 
     * @param int $user_id El ID del usuario a sincronizar
     * @param string $table La tabla que se actualizó ('users' o 'student_profiles')
     * @param string $status El nuevo estado
     * @return bool True si la sincronización tuvo éxito
     */
    public function syncVerificationStatus($user_id, $table, $status) {
        try {
            if ($table == 'users') {
                // Convertir status de users a verification_status de student_profiles
                $verification_status = $this->mapUserStatusToVerificationStatus($status);
                
                $query = "UPDATE student_profiles SET verification_status = ? WHERE user_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("si", $verification_status, $user_id);
                return $stmt->execute();
                
            } else if ($table == 'student_profiles') {
                // Convertir verification_status de student_profiles a status de users
                $user_status = $this->mapVerificationStatusToUserStatus($status);
                
                $query = "UPDATE users SET status = ? WHERE id = ? AND role = 'student'";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("si", $user_status, $user_id);
                return $stmt->execute();
            }
            
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Convierte el estado de users al estado de verification_status
     */
    private function mapUserStatusToVerificationStatus($status) {
        switch ($status) {
            case 'pending': return 'pending';
            case 'active': return 'verified';
            case 'blocked': return 'rejected';
            default: return 'pending';
        }
    }
    
    /**
     * Convierte el verification_status al estado de users
     */
    private function mapVerificationStatusToUserStatus($verification_status) {
        switch ($verification_status) {
            case 'pending': return 'pending';
            case 'verified': return 'active';
            case 'rejected': return 'blocked';
            default: return 'pending';
        }
    }
}
?>