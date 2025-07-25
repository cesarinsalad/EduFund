<?php
$pageTitle = "¡Donación Exitosa! | EduFund";
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">¡Donación Completada!</h1>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Tu donación ha sido procesada correctamente. Gracias por apoyar la educación y ayudar a estudiantes a alcanzar sus metas académicas.
            </p>
            
            <div class="flex flex-col space-y-4">
                <a href="index.php?page=campaigns" class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Explorar más campañas
                </a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=404.php" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Ver mis donaciones
                    </a>
                <?php else: ?>
                    <a href="index.php?page=register" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Regístrate para seguir donando
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>