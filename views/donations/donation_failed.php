<?php
$pageTitle = "Error en la Donación | EduFund";
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Error en el Procesamiento de la Donación</h1>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Lo sentimos, hubo un problema al procesar tu donación. El pago no se ha completado. 
                Por favor, intenta nuevamente o utiliza otro método de pago.
            </p>
            
            <div class="flex flex-col space-y-4">
                <?php if(isset($_GET['campaign_id']) && $_GET['campaign_id'] > 0): ?>
                    <a href="index.php?page=donate&campaign=<?php echo (int)$_GET['campaign_id']; ?>" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Intentar nuevamente
                    </a>
                    
                    <?php
                    // Intentar obtener el slug de la campaña si está disponible
                    $campaign_id = (int)$_GET['campaign_id'];
                    $campaign_slug = '';
                    
                    // Solo ejecutar esta parte si tenemos una conexión a la base de datos disponible
                    if(isset($db) && $campaign_id > 0) {
                        require_once 'models/Campaign.php';
                        $campaignModel = new Campaign($db);
                        $campaign = $campaignModel->getById($campaign_id);
                        if($campaign) {
                            $campaign_slug = $campaign['slug'];
                        }
                    }
                    
                    if(!empty($campaign_slug)): ?>
                        <a href="index.php?page=campaign&slug=<?php echo htmlspecialchars($campaign_slug); ?>" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                            Volver a la campaña
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=campaigns" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                            Ver campañas
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="index.php?page=campaigns" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Explorar campañas
                    </a>
                <?php endif; ?>
                
                <a href="index.php" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                    Volver al inicio
                </a>
            </div>
            
            <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                Si continúas teniendo problemas, por favor contacta con nuestro equipo de soporte.
            </p>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>