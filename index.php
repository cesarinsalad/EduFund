<?php
// Iniciar sesión
session_start();

// Incluir la configuración
require_once 'config/db.php';
require_once 'config/config.php';

// Inicializar base de datos
$database = new Database();
$db = $database->getConnection();

// Inicializar controladores
require_once 'controllers/CampaignController.php';
$campaignController = new CampaignController($db);

// Determinar la página solicitada
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Incluir header para la mayoría de las páginas
$skip_header = ['login_process', 'register_process', 'logout'];
if (!in_array($page, $skip_header)) {
    include 'includes/header.php';
}

// Enrutamiento básico
switch ($page) {
    case 'home':
        include 'views/home.php';
        break;
    
    case 'home-campaign':
        include 'views/home-campaign.php';
        break;
        
    case 'login':
        include 'views/auth/login.php';
        break;
        
    case 'login_process':
        include 'views/auth/login_process.php';
        break;
        
    case 'register':
        include 'views/auth/register.php';
        break;
        
    case 'register_process':
        include 'views/auth/register_process.php';
        break;

    case 'campaigns':
        $campaignController->listCampaigns();
        break;
            
    case 'campaign':
        $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
        $campaignController->viewCampaign($slug);
        break;
            
    case 'create_campaign':
        $campaignController->createCampaign();
        break;
            
    case 'my_campaigns':
        $campaignController->studentCampaigns();
        break;
        
    case 'logout':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        header('Location: index.php');
        exit;
        break;
        
    case 'student_dashboard':
        // Verificar si el usuario es estudiante
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: index.php?page=login');
            exit;
        }

        // Conexión con el modelo - Cargar datos del estudiante
        require_once 'models/StudentProfile.php';
        $database = new Database();
        $db = $database->getConnection();
            
        $student = new StudentProfile($db);
        $student->user_id = $_SESSION['user_id'];
        $student->readByUserId();
                
        // También podemos cargar las campañas del estudiante
        require_once 'models/Campaign.php';
        $campaign = new Campaign($db);
        $campaigns = $campaign->getByUserId($_SESSION['user_id']);
                
        // Cargar datos de donaciones recibidas
        $total_donations = 0; // Este valor se obtendría desde un modelo

        include 'views/user/student_dashboard.php';
        break;
        
    case 'donor_dashboard':
        // Verificar si el usuario es donante
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
            header('Location: index.php?page=login');
            exit;
        }

        // Conexión con el modelo - Cargar datos del donante
        require_once 'models/DonorProfile.php';
        require_once 'models/Donation.php';
            
        $database = new Database();
        $db = $database->getConnection();
           
        $donor = new DonorProfile($db);
        $donor->user_id = $_SESSION['user_id'];
        $donor->readByUserId();
            
        // Cargar donaciones realizadas
        $donation = new Donation($db);
        $donations = $donation->getByDonorId($_SESSION['user_id']);
        $total_donated = $donation->getTotalByDonorId($_SESSION['user_id']);
            
        // Cargar campañas recomendadas
        require_once 'models/Campaign.php';
        $campaign = new Campaign($db);
        $recommended_campaigns = $campaign->getRecommended();

        include 'views/user/donor_dashboard.php';
        break;
        
    case 'admin_dashboard':
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        require_once 'models/User.php';
        require_once 'models/Campaign.php';
        require_once 'models/Donation.php';
        require_once 'models/StudentProfile.php';
        
        $database = new Database();
        $db = $database->getConnection();
        
        // Obtener contadores
        $user = new User($db);
        $total_users = $user->count();
        
        $campaign = new Campaign($db);
        $total_campaigns = $campaign->count();
        
        $donation = new Donation($db);
        $total_donations = $donation->getTotalAmount();
        
        $student = new StudentProfile($db);
        $pending_verifications = $student->countPendingVerifications();
        
        // Obtener listas para las tablas
        $verifications = $student->getPendingVerifications();
        $recent_campaigns = $campaign->getRecent(10);

        include 'views/admin/admin_dashboard.php';
        break;
        
    default:
        // Página no encontrada
        include 'views/404.php';
        break;
}

// Incluir footer para la mayoría de las páginas
if (!in_array($page, $skip_header)) {
    include 'includes/footer.php';
}
?>