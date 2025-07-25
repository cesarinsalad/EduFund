<?php
// Iniciar el buffer de salida
ob_start(); 

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
require_once 'controllers/DonationController.php';
$donationController = new DonationController($db);
require_once 'controllers/AdminController.php';
$adminController = new AdminController($db);

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

    case 'donar_ahora':
        include 'views/donar_ahora.php';
        break;

    case 'faq':
        include 'views/faq.php';
        break;
    
    case 'nosotros':
        include 'views/nosotros.php';
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

    case 'donate':
        $campaign_id = isset($_GET['campaign']) ? (int)$_GET['campaign'] : 0;
        $donationController = new DonationController($db);
        $donationController->showDonationForm($campaign_id);
        break;
        
    case 'process_donation':
        $donationController = new DonationController($db);
        $donationController->processDonation();
        break;
        
    case 'donation_complete':
        $donationController = new DonationController($db);
        $donationController->completeDonation();
        break;
        
    case 'donation_cancel':
        $donationController = new DonationController($db);
        $donationController->cancelDonation();
        break;
        
    case 'user_donations':
        $donationController = new DonationController($db);
        $donationController->listUserDonations();
        break;
        
    case 'campaign_donations':
        $campaign_id = isset($_GET['campaign']) ? (int)$_GET['campaign'] : 0;
        $donationController = new DonationController($db);
        $donationController->listCampaignDonations($campaign_id);
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

    case 'delete_campaign':
        require_once 'controllers/CampaignController.php';
        $campaignController = new CampaignController($db);
        $campaignController->deleteCampaign();
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
        $donations = $donation->getByDonor($_SESSION['user_id']);
        $total_donated = $donation->getTotalByDonor($_SESSION['user_id']);
            
        // Cargar campañas recomendadas
        require_once 'models/Campaign.php';
        $campaign = new Campaign($db);
        $recommended_campaigns = $campaign->getRecommended();

        include 'views/user/donor_dashboard.php';
        break;
        
    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'admin_reports':
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
            
        $adminController->showReportGenerator();
        break;
    
    case 'generate_report':
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
            
        $adminController->generateReport();
        break;
    
    case 'verification_detail':
        $verification_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $adminController->verificationDetail($verification_id);
        break;
    
    case 'process_verification':
        $adminController->processVerification();
        break;
    
    case 'admin_statistics':
        $adminController->showStatistics();
        break;

        
    case 'admin_campaigns':
        $adminController->showPendingCampaigns();
        break;
        
    case 'campaign_verification':
        $campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $adminController->showCampaignDetail($campaign_id);
        break;
        
        case 'process_campaign_verification':
        $adminController->processCampaignVerification();
        break;

    case 'admin_approve_campaign':
        $campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $adminController->processCampaignVerification($campaign_id, 'verified');
        break;

    case 'admin_reject_campaign':
        $campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $adminController->processCampaignVerification($campaign_id, 'rejected');
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