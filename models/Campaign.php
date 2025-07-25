<?php
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
                 WHERE c.status = 'verified' AND c.featured = 1
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
        $query = "SELECT c.*, sp.full_name, sp.profile_picture, sp.institution, sp.educational_level 
                  FROM " . $this->table . " c
                  JOIN student_profiles sp ON c.student_id = sp.user_id
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
                 WHERE c.status = 'verified'";
        
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

    // Obtiene estadísticas de campañas por categoría
    public function getCategoriesStats() {
        $query = "SELECT 
                    category as name,
                    COUNT(*) as count
                  FROM " . $this->table . "
                  GROUP BY category
                  ORDER BY count DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        $results = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
    
        return $results;
    }

    // Cuenta el número de campañas creadas en un mes específico
    public function countByMonth($month) {
        $query = "SELECT 
                    COUNT(*) as total
                  FROM " . $this->table . "
                  WHERE DATE_FORMAT(created_at, '%Y-%m') = :month";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['total']);
    }

    public function getReportData($date_start = '', $date_end = '', $category = '') {
        try {
            $sql = "SELECT c.id as ID, c.title as Titulo, 
                    c.goal_amount as Meta, c.current_amount as Recaudado, 
                    c.status as Status, c.category as Categoria, 
                    c.created_at 'Fecha de Creacion', c.end_date 'Fecha de Fin',
                    u.username as Usuario,
                    u.email as Correo
                    FROM campaigns c
                    LEFT JOIN users u ON c.student_id = u.id
                    WHERE 1=1";
        
            $params = [];
        
            // Aplicar filtros si se proporcionan
            if (!empty($date_start)) {
                $sql .= " AND DATE(c.created_at) >= ?";
                $params[] = $date_start;
            }
        
            if (!empty($date_end)) {
                $sql .= " AND DATE(c.created_at) <= ?";
                $params[] = $date_end;
            }
        
            if (!empty($category)) {
                $sql .= " AND c.category = ?";
                $params[] = $category;
            }
        
            $sql .= " ORDER BY c.created_at DESC";
        
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
        
            $campaigns = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $campaigns[] = $row;
            }
        
            return $campaigns;
        } catch (PDOException $e) {
            error_log("Error obteniendo datos de campañas para reporte: " . $e->getMessage());
            return [];
        }
    }

public function getCategories() {
    try {
        $stmt = $this->conn->prepare("SELECT DISTINCT category FROM campaigns WHERE category IS NOT NULL ORDER BY category ASC");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category'];
        }
        
        return $categories;
    } catch (PDOException $e) {
        error_log("Error obteniendo categorías: " . $e->getMessage());
        return [];
    }
}

// Eliminar una campaña.
public function delete($campaign_id, $user_id) {
    try {
        // Verificar que la campaña pertenece al usuario
        $verify_query = "SELECT c.id, c.student_id, c.status, 
                        (SELECT COUNT(*) FROM donations WHERE campaign_id = c.id AND payment_status = 'completed') as donation_count
                        FROM campaigns c WHERE c.id = ?";
        $stmt = $this->conn->prepare($verify_query);
        $stmt->execute([$campaign_id]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        // Depuración
        error_log("Campaña obtenida: " . json_encode($campaign));
        error_log("Usuario en sesión: $user_id");

        // Si la campaña no existe
        if (!$campaign) {
            error_log("La campaña ID: $campaign_id no existe.");
            return false;
        }

        // Si la campaña no pertenece al usuario
        if ((int)$campaign['student_id'] !== (int)$user_id) {
            error_log("La campaña ID: $campaign_id pertenece a usuario ID: {$campaign['student_id']}, no a $user_id.");
            return false;
        }

        // Si la campaña tiene donaciones y está activa, no permitir la eliminación
        if ($campaign['donation_count'] > 0 && $campaign['status'] == 'verified') {
            error_log("La campaña ID: $campaign_id tiene donaciones y está activa, no se puede eliminar.");
            return false;
        }

        // Eliminar la campaña (marcar como eliminada)
        $delete_query = "UPDATE campaigns SET status = 'deleted', updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($delete_query);
        $result = $stmt->execute([$campaign_id]);

        if ($result) {
            error_log("Campaña ID: $campaign_id eliminada exitosamente.");
        } else {
            error_log("Error al eliminar campaña ID: $campaign_id.");
        }

        return $result;
    } catch (PDOException $e) {
        error_log("Error en delete: " . $e->getMessage());
        return false;
    }
}

public function getPendingVerifications() {
    $query = "SELECT c.*, u.username, sp.full_name 
              FROM " . $this->table . " c
              JOIN users u ON c.student_id = u.id
              JOIN student_profiles sp ON u.id = sp.user_id
              WHERE c.status = 'pending'
              ORDER BY c.created_at DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateVerificationStatus($campaign_id, $status) {
    if (!in_array($status, ['active', 'deleted', 'pending', 'verified', 'rejected'])) {
        return false;
    }
    
    $query = "UPDATE " . $this->table . " 
              SET status = :status,
                  updated_at = NOW()
              WHERE id = :campaign_id";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':campaign_id', $campaign_id);
    
    return $stmt->execute();
}
}
?>