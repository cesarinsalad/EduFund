<?php
/**
 * Modelo de Campaign
 * 
 * Implementación completa para la Fase 2
 */
class Campaign {
    private $conn;
    private $table = 'campaigns';
    
    // Propiedades básicas
    public $id;
    public $student_id;
    public $title;
    public $slug;
    public $description;
    public $goal_amount;
    public $current_amount;
    public $campaign_image;
    public $start_date;
    public $end_date;
    public $status;
    public $category;
    public $featured;
    public $created_at;
    public $updated_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Crea una nueva campaña
     */
    public function create() {
        try {
            // Generar slug desde el título
            $this->slug = $this->generateSlug($this->title);
            
            // Verificar que el slug sea único
            $this->slug = $this->ensureUniqueSlug($this->slug);
            
            $query = "INSERT INTO " . $this->table . " 
                     (student_id, title, slug, description, goal_amount, 
                     campaign_image, start_date, end_date, category, status) 
                     VALUES (:student_id, :title, :slug, :description, :goal_amount, 
                     :campaign_image, :start_date, :end_date, :category, 'pending')";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':student_id', $this->student_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindParam(':slug', $this->slug, PDO::PARAM_STR);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindParam(':goal_amount', $this->goal_amount, PDO::PARAM_STR);
            $stmt->bindParam(':campaign_image', $this->campaign_image, PDO::PARAM_STR);
            $stmt->bindParam(':start_date', $this->start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $this->end_date, PDO::PARAM_STR);
            $stmt->bindParam(':category', $this->category, PDO::PARAM_STR);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            // Para debug: echo $e->getMessage();
            return false;
        }
    }
    
    /**
     * Obtiene las campañas de un usuario específico
     */
    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE student_id = :user_id ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $campaigns = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }
    
    /**
     * Cuenta el número total de campañas
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    /**
     * Obtiene campañas recomendadas
     */
    public function getRecommended($limit = 4) {
        $query = "SELECT c.*, u.username, sp.full_name, sp.profile_picture 
                 FROM " . $this->table . " c
                 JOIN users u ON c.student_id = u.id
                 JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE c.status = 'active' AND c.featured = 1
                 ORDER BY c.created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $campaigns = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }
    
    /**
     * Obtiene las campañas más recientes
     */
    public function getRecent($limit = 5) {
        $query = "SELECT c.*, 
            sp.full_name AS student_name, 
            u.username
            FROM campaigns c
            JOIN users u ON c.student_id = u.id
            JOIN student_profiles sp ON u.id = sp.user_id
            ORDER BY c.created_at DESC 
            LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $campaigns = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }
    
    /**
     * Obtiene una campaña por su ID
     */
    public function getById($id) {
        $query = "SELECT c.*, u.username, sp.full_name, sp.profile_picture, sp.institution, sp.educational_level 
                 FROM " . $this->table . " c
                 JOIN users u ON c.student_id = u.id
                 JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE c.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }
    
    /**
     * Obtiene una campaña por su slug
     */
    public function getBySlug($slug) {
        $query = "SELECT c.*, u.username, sp.full_name, sp.profile_picture, sp.institution, sp.educational_level 
                 FROM " . $this->table . " c
                 JOIN users u ON c.student_id = u.id
                 JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE c.slug = :slug";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }
    
    /**
     * Obtiene campañas activas con posibilidad de filtrar por categoría
     */
    public function getActive($limit = 10, $offset = 0, $category = null) {
        $query = "SELECT c.*, u.username, sp.full_name, sp.profile_picture 
                 FROM " . $this->table . " c
                 JOIN users u ON c.student_id = u.id
                 JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE c.status = 'active'";
        
        if($category) {
            $query .= " AND c.category = :category";
        }
        
        $query .= " ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $campaigns = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }
    
    /**
     * Actualiza el monto actual de una campaña
     */
    public function updateAmount($id, $amount) {
        $query = "UPDATE " . $this->table . " SET current_amount = current_amount + :amount WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Genera un slug a partir de un título
     */
    private function generateSlug($title) {
        // Convertir a minúsculas y reemplazar espacios con guiones
        $slug = strtolower(trim($title));
        // Reemplazar caracteres no alfanuméricos con guiones
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        // Eliminar guiones duplicados
        $slug = preg_replace('/-+/', '-', $slug);
        // Eliminar guiones al inicio y final
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Asegura que el slug sea único añadiendo un sufijo numérico si es necesario
     */
    private function ensureUniqueSlug($slug) {
        $originalSlug = $slug;
        $count = 0;
        
        while(true) {
            $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE slug = :slug";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row['count'] == 0) {
                break;
            }
            
            $count++;
            $slug = $originalSlug . '-' . $count;
        }
        
        return $slug;
    }
}
?>