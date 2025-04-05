<?php
session_start();
header('Content-Type: application/json');

require_once '../models/Campaign.php';

// Función para sanitizar inputs
function sanitize_string($str) {
    return htmlspecialchars(strip_tags(trim($str)));
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$campaign = new Campaign();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
        
    case 'get_student_campaigns':
        if ($_SESSION['role'] !== 'student') {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }
        
        $studentCampaigns = $campaign->getStudentCampaigns($_SESSION['user_id']);
        echo json_encode(['success' => true, 'campaigns' => $studentCampaigns]);
        break;
        
    case 'get_campaign':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $campaignData = $campaign->getCampaignById($id);
        
        if (!$campaignData) {
            echo json_encode(['success' => false, 'message' => 'Campaña no encontrada']);
            exit;
        }
        
        echo json_encode(['success' => true, 'campaign' => $campaignData]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
?>