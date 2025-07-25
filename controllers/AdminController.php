<?php
require_once 'models/User.php';
require_once 'models/Campaign.php';
require_once 'models/Donation.php';
require_once 'models/StudentProfile.php';

class AdminController {
    private $db;
    private $userModel;
    private $campaignModel;
    private $donationModel;
    private $studentModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->campaignModel = new Campaign($db);
        $this->donationModel = new Donation($db);
        $this->studentModel = new StudentProfile($db);
    }
    
    public function dashboard() {
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Obtener contadores
        $total_users = $this->userModel->count();
        $total_campaigns = $this->campaignModel->count();
        $total_donations = $this->donationModel->getTotalAmount();
        $pending_verifications = $this->studentModel->countPendingVerifications();
        
        // Obtener listas para las tablas
        $verifications = $this->studentModel->getPendingVerifications();
        $recent_campaigns = $this->campaignModel->getRecent(10);
        
        include 'views/admin/admin_dashboard.php';
    }
    
    public function statistics() {
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Obtener estadísticas para gráficos
        $donation_stats = $this->donationModel->getMonthlyDonations();
        $donation_months = array_column($donation_stats, 'month');
        $donation_amounts = array_column($donation_stats, 'amount');
        
        $category_stats = $this->campaignModel->getCategoriesStats();
        
        include 'views/admin/statistics.php';
    }
    
    public function verificationDetail($verification_id) {
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $verification = $this->studentModel->getVerificationById($verification_id);
        
        if (!$verification) {
            include 'views/404.php';
            return;
        }
        
        include 'views/admin/verification/detail.php';
    }
    
    public function processVerification() {
        // Verificar permisos de administrador
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Obtener datos de la solicitud
        if (isset($_GET['id']) && isset($_GET['action'])) {
            // Es una acción rápida desde el dashboard
            $profile_id = (int)$_GET['id'];
            $action = $_GET['action'];
            
            if ($action === 'approve') {
                $status = 'verified';
            } else if ($action === 'reject') {
                $status = 'rejected';
            } else {
                $_SESSION['error'] = 'Acción no válida.';
                header('Location: index.php?page=admin_dashboard');
                exit;
            }
        } else if (isset($_POST['profile_id']) && isset($_POST['status'])) {
            // Es un envío de formulario con comentarios
            $profile_id = (int)$_POST['profile_id'];
            $status = $_POST['status'];
        } else {
            $_SESSION['error'] = 'Parámetros inválidos para la verificación.';
            header('Location: index.php?page=admin_dashboard');
            exit;
        }
        
        try {
            if ($this->studentModel->updateVerificationStatus($profile_id, $status, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'La verificación ha sido procesada correctamente.';
            } else {
                $_SESSION['error'] = 'Ha ocurrido un error al procesar la verificación.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            error_log("Error en verificación: " . $e->getMessage());
        }
        
        header('Location: index.php?page=admin_dashboard');
        exit;
    }

    public function showReportGenerator() {
        // Verificar permisos
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        require_once 'controllers/ReportController.php';
        $reportController = new ReportController($this->db);
        $reportController->showReportGenerator();
    }

    public function generateReport() {
        // Verificar permisos
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        require_once 'controllers/ReportController.php';
        $reportController = new ReportController($this->db);
        $reportController->generateReport();
    }

    // Método para mostrar estadísticas
    public function showStatistics() {
        // Verificar permisos
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        // Obtener datos para estadísticas
        require_once 'models/Donation.php';
        require_once 'models/Campaign.php';

        $donation = new Donation($this->db);
        $donation_stats = $donation->getMonthlyDonations();
        $donation_months = array_column($donation_stats, 'month');
        $donation_amounts = array_column($donation_stats, 'amount');
    
        $campaign = new Campaign($this->db);
        $category_stats = $campaign->getCategoriesStats();
    
        include 'views/admin/statistics.php';
    }

public function showPendingCampaigns() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }
    
    $pending_campaigns = $this->campaignModel->getPendingVerifications();
    include 'views/admin/campaigns/pending.php';
}

public function processCampaignVerification($campaign_id, $status) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    if (!in_array($status, ['verified', 'rejected'])) {
        $_SESSION['error'] = 'Estado no válido para la verificación.';
        header('Location: index.php?page=admin_campaigns');
        exit;
    }

    try {
        if ($this->campaignModel->updateVerificationStatus($campaign_id, $status)) {
            $_SESSION['success'] = 'La campaña ha sido ' . ($status === 'verified' ? 'aprobada' : 'rechazada') . ' correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo procesar la verificación de la campaña.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
        error_log("Error en verificación de campaña: " . $e->getMessage());
    }

    header('Location: index.php?page=admin_campaigns');
    exit;
}
}
?>