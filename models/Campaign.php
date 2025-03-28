<?php
/**
 * Modelo esqueleto de Campaign
 * 
 * NOTA: Esta es una versión simplificada para la Fase 1.
 * La implementación completa se realizará en la Fase 2.
 */
class Campaign {
    private $conn;
    private $table = 'campaigns';
    
    // Propiedades básicas
    public $id;
    public $title;
    public $description;
    public $goal_amount;
    public $current_amount;
    public $user_id;
    public $status;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtiene las campañas de un usuario específico
     * 
     * NOTA: En esta fase devuelve datos de muestra.
     * La implementación real usará consultas a la base de datos en la Fase 2.
     */
    public function getByUserId($user_id) {
        // Datos de muestra para la Fase 1
        // En la Fase 2, esto consultará las campañas reales de la base de datos
        
        // Devolver un array vacío por ahora
        return [];
        
        /* 
        // IMPLEMENTACIÓN FUTURA (FASE 2):
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        */
    }
    
    /**
     * Cuenta el número total de campañas
     * 
     * NOTA: En esta fase devuelve un valor de muestra.
     * La implementación real se hará en la Fase 2.
     */
    public function count() {
        // Valor de muestra para la Fase 1
        return 0;
    }
    
    /**
     * Obtiene campañas recomendadas
     * 
     * NOTA: En esta fase devuelve datos de muestra.
     * La implementación real se hará en fases posteriores.
     */
    public function getRecommended() {
        // Datos de muestra para la Fase 1
        return [];
    }
    
    /**
     * Obtiene las campañas más recientes
     * 
     * NOTA: En esta fase devuelve datos de muestra.
     * La implementación real se hará en la Fase 2.
     */
    public function getRecent($limit = 5) {
        // Datos de muestra para la Fase 1
        return [];
    }
}
?>