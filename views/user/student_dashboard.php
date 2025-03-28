<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Panel de estudiante</h1>
    
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
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Mis campañas -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Mis campañas</h2>
            
            <?php if(isset($campaigns) && !empty($campaigns)): ?>
                <ul class="space-y-2">
                    <?php foreach($campaigns as $c): ?>
                        <li class="border-b pb-2">
                            <a href="index.php?page=campaign&id=<?php echo $c['id']; ?>" class="font-semibold hover:text-blue-600"><?php echo $c['title']; ?></a>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($c['current_amount'] / $c['goal_amount']) * 100; ?>%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mt-1">
                                <span>$<?php echo number_format($c['current_amount'], 2); ?> recaudados</span>
                                <span>Meta: $<?php echo number_format($c['goal_amount'], 2); ?></span>
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
        
        <!-- Donaciones recibidas -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Donaciones recibidas</h2>
            <div class="flex items-center justify-center h-24">
                <span class="text-3xl font-bold text-gray-700">$<?php echo number_format($total_donations ?? 0, 2); ?></span>
            </div>
            <?php if(isset($campaigns) && !empty($campaigns)): ?>
                <div class="text-center mt-2">
                    <a href="index.php?page=my_donations" class="text-blue-600 hover:underline">Ver detalle de donaciones</a>
                </div>
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
    
    <!-- Pasos a seguir -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4 text-blue-600">Pasos a seguir</h2>
        
        <ol class="relative border-l border-gray-200 ml-3 space-y-6 pt-2">
            <!-- Paso 1: Verificación -->
            <li class="mb-10 ml-6">
                <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white
                    <?php if(isset($student) && $student->verification_status == 'verified'): ?>
                        bg-green-500 text-white
                    <?php elseif(isset($student) && $student->verification_status == 'rejected'): ?>
                        bg-red-500 text-white
                    <?php elseif(isset($student) && $student->verification_status == 'pending'): ?>
                        bg-yellow-500 text-white
                    <?php else: ?>
                        bg-gray-100 text-gray-500
                    <?php endif; ?>
                ">
                    <?php if(isset($student) && $student->verification_status == 'verified'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    <?php else: ?>
                        1
                    <?php endif; ?>
                </span>
                <h3 class="font-medium leading-tight">Verificación de cuenta</h3>
                <p class="text-sm text-gray-500">
                    <?php if(isset($student) && $student->verification_status == 'verified'): ?>
                        ¡Cuenta verificada correctamente!
                    <?php elseif(isset($student) && $student->verification_status == 'rejected'): ?>
                        Tu verificación fue rechazada. Por favor, envía nuevos documentos.
                    <?php else: ?>
                        Espera a que verifiquemos tu cuenta. Esto puede tomar hasta 24 horas.
                    <?php endif; ?>
                </p>
            </li>
            
            <!-- Paso 2: Completar perfil -->
            <li class="mb-10 ml-6">
                <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white
                    <?php if(isset($student) && !empty($student->bio) && !empty($student->profile_picture)): ?>
                        bg-green-500 text-white
                    <?php else: ?>
                        bg-gray-100 text-gray-500
                    <?php endif; ?>
                ">
                    <?php if(isset($student) && !empty($student->bio) && !empty($student->profile_picture)): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    <?php else: ?>
                        2
                    <?php endif; ?>
                </span>
                <h3 class="font-medium leading-tight">Completar información de perfil</h3>
                <p class="text-sm text-gray-500">Añade una foto de perfil y una biografía detallada para aumentar tus posibilidades de recibir apoyo.</p>
            </li>
            
            <!-- Paso 3: Crear campaña -->
            <li class="mb-10 ml-6">
                <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -left-4 ring-4 ring-white
                    <?php if(isset($campaigns) && !empty($campaigns)): ?>
                        bg-green-500 text-white
                    <?php else: ?>
                        bg-gray-100 text-gray-500
                    <?php endif; ?>
                ">
                    <?php if(isset($campaigns) && !empty($campaigns)): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    <?php else: ?>
                        3
                    <?php endif; ?>
                </span>
                <h3 class="font-medium leading-tight">Crear tu primera campaña</h3>
                <p class="text-sm text-gray-500">Detalla tus necesidades educativas y establece una meta realista.</p>
            </li>
            
            <!-- Paso 4: Compartir campaña -->
            <li class="ml-6">
                <span class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -left-4 ring-4 ring-white text-gray-500">
                    4
                </span>
                <h3 class="font-medium leading-tight">Compartir en redes sociales</h3>
                <p class="text-sm text-gray-500">Difunde tu campaña para llegar a más personas y aumentar tus posibilidades de recibir donaciones.</p>
            </li>
        </ol>
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