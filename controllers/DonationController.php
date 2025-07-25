<?php
require_once 'models/Campaign.php';
require_once 'models/Donation.php';
require_once 'services/PayPalService.php';

class DonationController {
    private $campaignModel;
    private $donationModel;
    private $paypalService;
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->campaignModel = new Campaign($db);
        $this->donationModel = new Donation($db);
        $this->paypalService = new PayPalService();
    }
    
    // Mostrar formulario de donación.
    public function showDonationForm($campaign_id) {
        if ($campaign_id <= 0) {
            include 'views/404.php'; // Mostrar página 404 si el ID no es válido
            return;
        }
    
        // Obtener información de la campaña
        require_once 'models/Campaign.php';
        $campaignModel = new Campaign($this->db);
        $campaign = $campaignModel->getById($campaign_id);
    
        if (!$campaign || $campaign['status'] !== 'verified') {
            include 'views/404.php'; // Mostrar página 404 si la campaña no existe o no está verificada
            return;
        }
    
        // Incluir el formulario de donación
        include 'views/donations/donation_form.php';
    }
    
    // Procesar donación.
    public function processDonation() {
        // Validar datos del formulario
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=campaigns');
            exit;
        }
        
        // Obtener y validar datos.
        $campaign_id = isset($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : 0;
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
        $anonymous = isset($_POST['anonymous']) ? (bool)$_POST['anonymous'] : false;
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
        
        // Validar donante.
        $donor_id = null;
        $donor_name = isset($_POST['donor_name']) ? trim($_POST['donor_name']) : '';
        $donor_email = isset($_POST['donor_email']) ? trim($_POST['donor_email']) : '';
        
        // Si hay sesión activa y es donante, usar el ID del usuario.
        if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'donor') {
            $donor_id = $_SESSION['user_id'];
        }
        
        // Validaciones.
        $errors = [];
        
        if($campaign_id <= 0) {
            $errors[] = "Campaña inválida";
        }
        
        if($amount <= 0) {
            $errors[] = "El monto debe ser mayor a cero";
        }
        
        if(!$donor_id && (empty($donor_name) || empty($donor_email))) {
            $errors[] = "Nombre y correo electrónico son requeridos para donantes no registrados";
        }
        
        if(!empty($donor_email) && !filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Correo electrónico inválido";
        }
        
        // Si hay errores, volver al formulario.
        if(!empty($errors)) {
            $campaign = $this->campaignModel->getById($campaign_id);
            include('views/donations/donation_form.php');
            return;
        }
        
        // Obtener información de la campaña.
        $campaign = $this->campaignModel->getById($campaign_id);
        
        if(!$campaign || $campaign['status'] != 'verified') {
            $errors[] = "La campaña no está disponible para donaciones";
            include('views/donations/donation_form.php');
            return;
        }
        
        // URLs de retorno.
        $return_url = 'http://edufund.test/index.php?page=donation_complete';
        $cancel_url = 'http://edufund.test/index.php?page=donation_cancel&campaign_id=' . $campaign_id;
        
        try {
            // Crear orden en PayPal
            error_log("Intentando crear orden en PayPal...");
            $order = $this->paypalService->createOrder(
                $amount, 
                $campaign['title'], 
                $return_url, 
                $cancel_url
            );

            if (isset($order['id'])) {
                error_log("Orden creada en PayPal con ID: " . $order['id']);
                // Guardar datos de la donación en sesión.
                $_SESSION['donation_data'] = [
                    'campaign_id' => $campaign_id,
                    'donor_id' => $donor_id,
                    'amount' => $amount,
                    'payment_id' => $order['id'],
                    'anonymous' => $anonymous,
                    'message' => $message,
                    'donor_name' => $donor_name,
                    'donor_email' => $donor_email
                ];
                
                // Crear registro de donación con estado 'pending'.
                $this->donationModel->campaign_id = $campaign_id;
                $this->donationModel->donor_id = $donor_id;
                $this->donationModel->amount = $amount;
                $this->donationModel->payment_id = $order['id'];
                $this->donationModel->payment_status = 'pending';
                $this->donationModel->anonymous = $anonymous;
                $this->donationModel->message = $message;
                $this->donationModel->donor_name = $donor_name;
                $this->donationModel->donor_email = $donor_email;
                
                $this->donationModel->create();
                
                // Redirigir a PayPal para completar el pago.
                foreach($order['links'] as $link) {
                    if($link['rel'] == 'approve') {
                        header('Location: ' . $link['href']);
                        exit;
                    }
                }
            } else {
                error_log("Error: No se pudo crear la orden en PayPal.");
                $errors[] = "Error al procesar la donación. Por favor intenta nuevamente.";
                include('views/donations/donation_form.php');
                return;
            }
        } catch (Exception $e) {
            error_log("Excepción capturada: " . $e->getMessage());
            $errors[] = "Error: " . $e->getMessage();
            include('views/donations/donation_form.php');
            return;
        }
    }
    
    // Completar donación después de obtener aprobación desde PayPal.
    public function completeDonation() {
        if(!isset($_GET['token']) || empty($_GET['token'])) {
            header('Location: index.php');
            exit;
        }
        
        $payment_id = $_GET['token'];
        
        try {
            // Capturar el pago.
            $result = $this->paypalService->capturePayment($payment_id);
            
            if($result['status'] == 'COMPLETED') {
                // Actualizar estado de la donación.
                $this->donationModel->payment_id = $payment_id;
                $this->donationModel->payment_status = 'completed';
                
                if($this->donationModel->updatePaymentStatus()) {
                    // Limpiar datos de sesión.
                    if(isset($_SESSION['donation_data'])) {
                        unset($_SESSION['donation_data']);
                    }
                    
                    // Mostrar vista de éxito.
                    include('views/donations/donation_success.php');
                    return;
                }
            } else {
                // Pago no completado.
                include('views/donations/donation_failed.php');
                return;
            }
        } catch(Exception $e) {
            // Error al procesar el pago.
            include('views/donations/donation_failed.php');
            return;
        }
    }
    
    // Cancelar donación.
    public function cancelDonation() {
        $campaign_id = isset($_GET['campaign_id']) ? (int)$_GET['campaign_id'] : 0;
        include('views/donations/donation_cancelled.php');
    }
    
    // Muestra las donaciones del usuario actual.
    public function listUserDonations() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $donor_id = $_SESSION['user_id'];
        $donations = $this->donationModel->getByDonor($donor_id);
        
        include('views/donations/user_donations.php');
    }
    
    // Muestra las donaciones de una campaña específica.
    public function listCampaignDonations($campaign_id) {
        $campaign = $this->campaignModel->getById($campaign_id);
        
        if(!$campaign) {
            include('views/404.php');
            return;
        }
        
        // Verificar permisos (solo el estudiante dueño o un admin pueden ver todas las donaciones de una campaña en específico).
        $canViewAll = false;
        if(isset($_SESSION['user_id'])) {
            if($_SESSION['role'] == 'admin' || ($_SESSION['role'] == 'student' && $_SESSION['user_id'] == $campaign['student_id'])) {
                $canViewAll = true;
            }
        }
        
        $donations = $this->donationModel->getByCampaign($campaign_id, $canViewAll);
        
        include('views/donations/campaign_donations.php');
    }
}
?>