<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center dark:text-white">Panel de Estudiante</h1>
    <!-- <p><//?php echo("{$_SESSION['user_id']}"."<br />");?></p> -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($student) && $student): ?>
        <!-- Bloque de estado de verificación -->
        <div class="
            <?php 
                if($student->verification_status == 'verified') echo 'bg-green-100 border-green-500 text-green-700';
                elseif($student->verification_status == 'rejected') echo 'bg-red-100 border-red-500 text-red-700';
                else echo 'bg-yellow-100 border-yellow-500 text-yellow-700';
            ?> 
            border-l-4 p-4 mb-6" role="alert">
            <p class="font-bold">Estado de verificación: <?php echo ucfirst($student->verification_status); ?></p>
            <?php if($student->verification_status == 'pending'): ?>
                <p>Tu cuenta está pendiente de verificación. Te notificaremos cuando revisemos tus documentos.</p>
            <?php elseif($student->verification_status == 'verified'): ?>
                <p>¡Tu cuenta ha sido verificada! Ya puedes crear campañas.</p>
            <?php elseif($student->verification_status == 'rejected'): ?>
                <p>Tu verificación fue rechazada. Motivo: <?php echo $student->verification_notes; ?></p>
                <a href="index.php?page=submit_verification" class="mt-2 inline-block text-red-700 font-bold hover:underline">Enviar nuevos documentos</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Mis campañas -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Mis campañas</h2>
            
            <?php if(isset($campaigns) && !empty($campaigns)): ?>
                <ul class="space-y-4">
                    <?php foreach($campaigns as $c): ?>
                        <li class="border-b pb-4">
                            <div class="flex justify-between items-start mb-2">
                                <a href="index.php?page=campaign&slug=<?php echo $c['slug']; ?>" class="font-semibold hover:text-blue-600"><?php echo $c['title']; ?></a>
                                <div class="flex space-x-2">
                                    <a href="index.php?page=edit_campaign&id=<?php echo $c['id']; ?>" class="text-blue-600 hover:text-blue-800" title="Editar campaña">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="index.php?page=delete_campaign&id=<?php echo $c['id']; ?>" 
                                       class="text-red-600 hover:text-red-800" 
                                       title="Eliminar campaña"
                                       onclick="return confirm('¿Estás seguro de querer eliminar esta campaña? Esta acción no se puede deshacer.')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($c['current_amount'] / $c['goal_amount']) * 100; ?>%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mt-1">
                                <span>$<?php echo number_format($c['current_amount'], 2); ?> recaudados</span>
                                <span>Meta: $<?php echo number_format($c['goal_amount'], 2); ?></span>
                            </div>
                            <div class="mt-2">
                                <span class="inline-block px-2 py-1 text-xs rounded
                                    <?php 
                                        if($c['status'] == 'verified') echo ' bg-green-100 text-green-800';
                                        elseif($c['status'] == 'pending') echo ' bg-yellow-100 text-yellow-800';
                                        elseif($c['status'] == 'completed') echo ' bg-blue-100 text-blue-800';
                                        else echo ' bg-red-100 text-red-800';
                                    ?>">
                                    <?php echo ucfirst($c['status']); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-600 mb-4">No tienes campañas activas</p>
            <?php endif; ?>
            
            <?php if(isset($student) && $student->verification_status == 'verified'): ?>
                <a href="index.php?page=create_campaign" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear campaña</a>
            <?php elseif(isset($student) && $student->verification_status == 'pending'): ?>
                <p class="text-sm italic text-gray-500 mt-2">Podrás crear campañas una vez que tu cuenta sea verificada.</p>
            <?php endif; ?>
        </div>
        
        <!-- Mi perfil -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Mi perfil</h2>
            <?php if(isset($student) && $student): ?>
                <div class="space-y-2 mb-4">
                    <p><span class="font-bold">Nombre:</span> <?php echo $student->full_name; ?></p>
                    <p><span class="font-bold">Institución:</span> <?php echo $student->institution; ?></p>
                    <p><span class="font-bold">Nivel educativo:</span> <?php echo ucfirst($student->educational_level); ?></p>
                </div>
            <?php endif; ?>
            
            <a href="index.php?page=edit_profile" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Editar perfil</a>
        </div>
    </div>
    
    <?php if(isset($campaigns) && !empty($campaigns)): ?>
    <!-- Estadísticas rápidas -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-blue-600">Estadísticas de campañas</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold mb-2">Campaña más exitosa</h3>
                <p class="text-lg font-bold">
                    <?php
                    $most_successful = null;
                    $highest_percentage = 0;
                    
                    foreach($campaigns as $c) {
                        $percentage = ($c['current_amount'] / $c['goal_amount']) * 100;
                        if($percentage > $highest_percentage) {
                            $highest_percentage = $percentage;
                            $most_successful = $c;
                        }
                    }
                    
                    if($most_successful) {
                        echo $most_successful['title'] . ' (' . number_format($highest_percentage, 0) . '%)';
                    } else {
                        echo 'Sin datos suficientes';
                    }
                    ?>
                </p>
            </div>
            
            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold mb-2">Campaña más reciente</h3>
                <p class="text-lg font-bold">
                    <?php
                    $newest_campaign = reset($campaigns);
                    if($newest_campaign) {
                        echo $newest_campaign['title'];
                    } else {
                        echo 'Sin datos';
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>