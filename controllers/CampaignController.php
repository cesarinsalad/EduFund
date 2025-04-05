<?php
require_once 'models/Campaign.php';

class CampaignController {
    private $campaignModel;
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->campaignModel = new Campaign($db);
    }
    
    public function createCampaign() {
        // Verificar si hay sesión activa y es un estudiante
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Validar formulario
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include_once 'views/campaigns/create.php';
            return;
        }
        
        // Procesar datos del formulario
        $this->campaignModel->student_id = $_SESSION['user_id'];
        $this->campaignModel->title = trim($_POST['title']);
        $this->campaignModel->description = trim($_POST['description']);
        $this->campaignModel->goal_amount = floatval($_POST['goal_amount']);
        $this->campaignModel->category = trim($_POST['category']);
        $this->campaignModel->start_date = $_POST['start_date'];
        $this->campaignModel->end_date = $_POST['end_date'];
        
        // Validación de datos
        $errors = [];
        
        if(empty($this->campaignModel->title)) {
            $errors[] = "El título es obligatorio";
        }
        if(empty($this->campaignModel->description)) {
            $errors[] = "La descripción es obligatoria";
        }
        if($this->campaignModel->goal_amount <= 0) {
            $errors[] = "El monto objetivo debe ser mayor a cero";
        }
        if(empty($this->campaignModel->category)) {
            $errors[] = "Debes seleccionar una categoría";
        }
        if(strtotime($this->campaignModel->end_date) <= strtotime($this->campaignModel->start_date)) {
            $errors[] = "La fecha de finalización debe ser posterior a la fecha de inicio";
        }
        
        // Procesar imagen si existe
        $this->campaignModel->campaign_image = '';
        if(isset($_FILES['campaign_image']) && $_FILES['campaign_image']['error'] === 0) {
            $upload_dir = 'assets/images/campaigns/';
            
            // Crear directorio si no existe
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['campaign_image']['name']);
            $target_file = $upload_dir . $file_name;
            
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            if(!in_array($_FILES['campaign_image']['type'], $allowed_types)) {
                $errors[] = "Solo se permiten imágenes JPG, JPEG y PNG";
            } else if($_FILES['campaign_image']['size'] > 2000000) { // 2MB limit
                $errors[] = "La imagen no debe exceder 2MB";
            } else if(move_uploaded_file($_FILES['campaign_image']['tmp_name'], $target_file)) {
                $this->campaignModel->campaign_image = $target_file;
            } else {
                $errors[] = "Error al subir la imagen";
            }
        }
        
        // Si hay errores, volver al formulario
        if(!empty($errors)) {
            include_once 'views/campaigns/create.php';
            return;
        }
        
        // Crear campaña
        if($this->campaignModel->create()) {
            $slug = $this->campaignModel->slug;
            header('Location: index.php?page=campaign&slug=' . $slug);
            exit;
        } else {
            $errors[] = "Error al crear la campaña. Inténtalo de nuevo.";
            include_once 'views/campaigns/create.php';
        }
    }
    
    public function viewCampaign($slug = null) {
        if(!$slug) {
            exit;
        }
        
        $campaign = $this->campaignModel->getBySlug($slug);
        
        if(!$campaign) {
            // Campaña no encontrada
            include_once 'views/404.php';
            return;
        }
        
        // Incluir vista de campaña
        include_once 'views/campaigns/view.php';
    }
    
    public function listCampaigns() {
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $limit = 8; // Campañas por página
        $offset = ($page - 1) * $limit;
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        
        $campaigns = $this->campaignModel->getActive($limit, $offset, $category);
        
        // Incluir vista de listado
        include_once 'views/campaigns/list.php';
    }
    
    public function studentCampaigns() {
        // Verificar si hay sesión activa y es un estudiante
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $student_id = $_SESSION['user_id'];
        $campaigns = $this->campaignModel->getByUserId($student_id);
        
        // Incluir vista de campañas del estudiante
        include_once 'views/campaigns/student_campaigns.php';
    }
}
?>