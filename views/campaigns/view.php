<?php
include_once 'includes/header.php';

// Calcular porcentaje de avance
$percentage = ($campaign['goal_amount'] > 0) ? min(100, round(($campaign['current_amount'] / $campaign['goal_amount']) * 100)) : 0;

// Formatear fechas
$start_date = date('d M, Y', strtotime($campaign['start_date']));
$end_date = date('d M, Y', strtotime($campaign['end_date']));

// Días restantes
$now = new DateTime();
$end = new DateTime($campaign['end_date']);
$interval = $now->diff($end);
$days_remaining = $interval->invert ? 0 : $interval->days;
?>

<div class="container mx-auto px-4 py-8">
    <!-- Información de la campaña -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <!-- Encabezado de la campaña -->
        <div class="relative">
            <?php if(!empty($campaign['campaign_image'])): ?>
                <img src="<?php echo htmlspecialchars($campaign['campaign_image']); ?>" alt="<?php echo htmlspecialchars($campaign['title']); ?>" class="w-full h-64 object-cover">
            <?php else: ?>
                <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-gray-500 dark:text-gray-400">Sin imagen</span>
                </div>
            <?php endif; ?>
            
            <div class="absolute top-0 right-0 mt-4 mr-4">
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    <?php echo htmlspecialchars($campaign['category']); ?>
                </span>
            </div>
        </div>
        
        <div class="p-6 dark:bg-gray-900">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4"><?php echo htmlspecialchars($campaign['title']); ?></h1>
            
            <!-- Perfil del estudiante -->
            <div class="flex items-center mb-6">
                <?php if(!empty($campaign['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($campaign['profile_picture']); ?>" alt="<?php echo htmlspecialchars($campaign['full_name']); ?>" class="w-12 h-12 rounded-full mr-4">
                <?php else: ?>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <span class="text-blue-500 font-bold"><?php echo strtoupper(substr($campaign['full_name'], 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><?php echo htmlspecialchars($campaign['full_name']); ?></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <?php echo htmlspecialchars($campaign['institution']); ?> | <?php echo htmlspecialchars($campaign['educational_level']); ?>
                    </p>
                </div>
            </div>
            
            <!-- Barra de progreso -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">$<?php echo number_format($campaign['current_amount'], 2); ?></span>
                    <span class="text-gray-600 dark:text-gray-400">de $<?php echo number_format($campaign['goal_amount'], 2); ?></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                    <div class="bg-blue-600 h-4 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                </div>
                <div class="flex justify-between mt-2 text-sm">
                    <span class="text-gray-600 dark:text-gray-400"><?php echo $percentage; ?>% completado</span>
                    <span class="text-gray-600 dark:text-gray-400"><?php echo $days_remaining; ?> días restantes</span>
                </div>
            </div>
            
            <!-- Fechas y estado -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de inicio</p>
                    <p class="font-semibold text-gray-800 dark:text-white"><?php echo $start_date; ?></p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fecha de finalización</p>
                    <p class="font-semibold text-gray-800 dark:text-white"><?php echo $end_date; ?></p>
                </div>
            </div>
            
            <!-- Descripción -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Acerca de esta campaña</h2>
                <div class="prose prose-blue max-w-none dark:prose-invert text-black dark:text-white">
                    <?php echo nl2br(htmlspecialchars($campaign['description'])); ?>
                </div>
            </div>
            
            <!-- Botón de donar (se mostrará sólo si la campaña está activa) -->
            <?php if($campaign['status'] == 'verified'): ?>
                <div class="mt-6">
                    <a href="index.php?page=donate&campaign=<?php echo $campaign['id']; ?>" 
                       class="w-full block text-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                        Apoyar esta Campaña
                    </a>
                </div>
            <?php elseif($campaign['status'] == 'pending'): ?>
                <div class="mt-6 bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                    <p class="font-semibold">Esta campaña está pendiente de aprobación por nuestro equipo.</p>
                </div>
            <?php elseif($campaign['status'] == 'completed'): ?>
                <div class="mt-6 bg-green-100 text-green-800 p-4 rounded-lg">
                    <p class="font-semibold">¡Esta campaña ha sido completada con éxito!</p>
                </div>
            <?php endif; ?>

            <!-- Lista de donaciones -->
            <div class="mt-12 border-t pt-8 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">
                    Donaciones (<?php echo $donorsCount; ?>)
                </h2>

                <?php if(empty($donations)): ?>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Esta campaña aún no ha recibido donaciones. ¡Sé el primero en apoyar!
                        </p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach($donations as $donation): ?>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold">
                                            <?php echo substr($donation['display_name'], 0, 1); ?>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-sm font-medium text-gray-800 dark:text-white">
                                                <?php echo htmlspecialchars($donation['display_name']); ?>
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo date('d M Y', strtotime($donation['donation_date'])); ?>
                                            </p>
                                        </div>
                                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">
                                            $<?php echo number_format($donation['amount'], 2); ?>
                                        </p>
                                        <?php if(!empty($donation['message'])): ?>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                                                <?php echo nl2br(htmlspecialchars($donation['message'])); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>