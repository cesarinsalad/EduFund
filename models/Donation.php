<?php
/**
 * Modelo esqueleto de Donation
 * 
 * NOTA: Esta es una versión simplificada para la Fase 1.
 * La implementación completa se realizará en la Fase 3.
 */
class Donation {
    private $conn;
    private $table = 'donations';
    
    // Propiedades básicas
    public $id;
    public $amount;
    public $campaign_id;
    public $donor_id;
    public $status;
    public $payment_method;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtiene las donaciones realizadas por un donante
     * 
     * NOTA: En esta fase devuelve datos de muestra.
     * La implementación real usará consultas a la base de datos en la Fase 3.
     */
    public function getByDonorId($donor_id) {
        // Datos de muestra para la Fase 1
        return [];
    }
    
    /**
     * Obtiene el total donado por un usuario
     * 
     * NOTA: En esta fase devuelve un valor de muestra.
     * La implementación real se hará en la Fase 3.
     */
    public function getTotalByDonorId($donor_id) {
        // Valor de muestra para la Fase 1
        return 0;
    }
    
    /**
     * Obtiene el monto total de donaciones en toda la plataforma
     * 
     * NOTA: En esta fase devuelve un valor de muestra.
     * La implementación real se hará en la Fase 3.
     */
    public function getTotalAmount() {
        // Valor de muestra para la Fase 1
        return 0;
    }
}
?>