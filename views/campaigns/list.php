<?php
// Título de la página
$pageTitle = "Explorar Campañas | EduFund";
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0">Explorar Campañas</h1>
        
        <!-- Filtros -->
        <div class="flex flex-wrap gap-2">
            <a href="index.php?page=campaigns" class="px-4 py-2 rounded-full <?php echo !isset($_GET['category']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'; ?>">
                Todas
            </a>
            <a href="index.php?page=campaigns&category=Licenciatura" class="px-4 py-2 rounded-full <?php echo (isset($_GET['category']) && $_GET['category'] == 'Licenciatura') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'; ?>">
                Licenciatura
            </a>
            <a href="index.php?page=campaigns&category=Maestría" class="px-4 py-2 rounded-full <?php echo (isset($_GET['category']) && $_GET['category'] == 'Maestría') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'; ?>">
                Maestría
            </a>
            <a href="index.php?page=campaigns&category=Doctorado" class="px-4 py-2 rounded-full <?php echo (isset($_GET['category']) && $_GET['category'] == 'Doctorado') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'; ?>">
                Doctorado
            </a>
            <a href="index.php?page=campaigns&category=Material+Educativo" class="px-4 py-2 rounded-full <?php echo (isset($_GET['category']) && $_GET['category'] == 'Material Educativo') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'; ?>">
                Material Educativo
            </a>
        </div>
    </div>
    
    <?php if(empty($campaigns)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No se encontraron campañas</h2>
            <p class="text-gray-600 dark:text-gray-400">
                <?php echo isset($_GET['category']) ? 'No hay campañas disponibles en esta categoría.' : 'No hay campañas disponibles en este momento.'; ?>
            </p>
        </div>
    <?php else: ?>
        <!-- Grid de campañas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach($campaigns as $campaign): 
                // Calcular porcentaje
                $percentage = ($campaign['goal_amount'] > 0) ? min(100, round(($campaign['current_amount'] / $campaign['goal_amount']) * 100)) : 0;
                
                // Días restantes
                $now = new DateTime();
                $end = new DateTime($campaign['end_date']);
                $interval = $now->diff($end);
                $days_remaining = $interval->invert ? 0 : $interval->days;
            ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="index.php?page=campaign&slug=<?php echo $campaign['slug']; ?>">
                        <?php if(!empty($campaign['campaign_image'])): ?>
                            <img src="<?php echo htmlspecialchars($campaign['campaign_image']); ?>" alt="<?php echo htmlspecialchars($campaign['title']); ?>" class="w-full h-40 object-cover">
                        <?php else: ?>
                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">Sin imagen</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                                    <?php echo htmlspecialchars($campaign['category']); ?>
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo $days_remaining; ?> días</span>
                            </div>
                            
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 line-clamp-2" title="<?php echo htmlspecialchars($campaign['title']); ?>">
                                <?php echo htmlspecialchars($campaign['title']); ?>
                            </h2>
                            
                            <div class="flex items-center mb-3">
                                <?php if(!empty($campaign['profile_picture'])): ?>
                                    <img src="<?php echo htmlspecialchars($campaign['profile_picture']); ?>" alt="<?php echo htmlspecialchars($campaign['full_name']); ?>" class="w-6 h-6 rounded-full mr-2">
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                        <span class="text-blue-500 text-xs font-bold"><?php echo strtoupper(substr($campaign['full_name'], 0, 1)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($campaign['full_name']); ?></span>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="font-semibold text-gray-800 dark:text-white">$<?php echo number_format($campaign['current_amount'], 2); ?></span>
                                <span class="text-gray-600 dark:text-gray-400"><?php echo $percentage; ?>% de $<?php echo number_format($campaign['goal_amount'], 2); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Paginación -->
        <?php 
        // Código de paginación aquí si es necesario
        ?>
    <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>