<?php
session_start();
header('Content-Type: application/json');

require_once '../config/db.php';
require_once '../models/Campaign.php';

// Inicializar base de datos
$database = new Database();
$db = $database->getConnection();

// Inicializar modelo
$campaign = new Campaign($db);

// Determinar acción
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_featured':
        $featuredCampaigns = $campaign->getRecommended(3); // Obtener 3 campañas destacadas
        echo json_encode([
            'success' => true,
            'campaigns' => $featuredCampaigns
        ]);
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Acción no especificada'
        ]);
        break;
}
?>