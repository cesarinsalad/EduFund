<?php
$pageTitle = "Donación Cancelada | EduFund";
include_once 'includes/header.php';

// Obtener la campaña si está disponible
$campaign = null;
if(isset($campaign_id) && $campaign_id > 0) {
    require_once 'models/Campaign.php';
    require_once 'config/db.php';
    $database = new Database();
    $db = $database->getConnection();
    $campaignModel = new Campaign($db);
    $campaign = $campaignModel->getById($campaign_id);
}
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Donación Cancelada</h1>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Has cancelado el proceso de donación. Si encontraste algún problema o tienes preguntas, no dudes en contactarnos.
            </p>
            
            <div class="flex flex-col space-y-4">
                <?php if($campaign): ?>
                    <a href="index.php?page=campaign&slug=<?php echo $campaign['slug']; ?>" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Volver a la campaña
                    </a>
                <?php else: ?>
                    <a href="index.php?page=campaigns" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Explorar campañas
                    </a>
                <?php endif; ?>
                
                <a href="index.php" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>