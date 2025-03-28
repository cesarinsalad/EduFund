<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Panel de donante</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Mis donaciones -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Mis donaciones</h2>
            
            <?php if(isset($donations) && !empty($donations)): ?>
                <ul class="space-y-2">
                    <?php foreach($donations as $donation): ?>
                        <li class="border-b pb-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="index.php?page=campaign&id=<?php echo $donation['campaign_id']; ?>" class="font-semibold hover:text-blue-600">
                                        <?php echo $donation['campaign_title']; ?>
                                    </a>
                                    <p class="text-sm text-gray-600">
                                        <?php echo date('d/m/Y', strtotime($donation['created_at'])); ?>
                                    </p>
                                </div>
                                <span class="font-bold text-green-600">
                                    $<?php echo number_format($donation['amount'], 2); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-4 text-center">
                    <a href="index.php?page=my_donations" class="text-blue-600 hover:underline">Ver todas mis donaciones</a>
                </div>
            <?php else: ?>
                <div class="text-center py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 mb-4">No has realizado donaciones aún</p>
                    <a href="index.php?page=explore_campaigns" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Explorar campañas
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Total donado -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Total donado</h2>
            <div class="flex items-center justify-center h-24">
                <span class="text-3xl font-bold text-gray-700">$<?php echo number_format($total_donated ?? 0, 2); ?></span>
            </div>
            <div class="text-center mt-2">
                <p class="text-sm text-gray-500">
                    <?php if(isset($donations) && !empty($donations)): ?>
                        Has realizado <?php echo count($donations); ?> donaciones
                    <?php else: ?>
                        ¡Haz tu primera donación hoy!
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <!-- Mi perfil -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Mi perfil</h2>
            <?php if(isset($donor) && $donor): ?>
                <div class="space-y-2 mb-4">
                    <p>
                        <span class="font-bold">Nombre:</span> 
                        <?php echo !empty($donor->full_name) ? $donor->full_name : 'No especificado'; ?>
                    </p>
                    <p>
                        <span class="font-bold">Nombre visible:</span> 
                        <?php echo !empty($donor->display_name) ? $donor->display_name : $_SESSION['username']; ?>
                    </p>
                    <p>
                        <span class="font-bold">Método de pago preferido:</span> 
                        <?php echo !empty($donor->preferred_payment_method) ? $donor->preferred_payment_method : 'No especificado'; ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <a href="index.php?page=edit_donor_profile" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Editar perfil
            </a>
        </div>
    </div>
    
    <!-- Campañas recomendadas -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-bold mb-4 text-blue-600">Campañas recomendadas</h2>
        <p class="text-gray-600 italic mb-4">Basado en tus intereses y actividad previa</p>
        
        <?php if(isset($recommended_campaigns) && !empty($recommended_campaigns)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach($recommended_campaigns as $campaign): ?>
                    <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <?php if(!empty($campaign['image'])): ?>
                            <img src="<?php echo $campaign['image']; ?>" alt="<?php echo $campaign['title']; ?>" class="w-full h-40 object-cover">
                        <?php else: ?>
                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-4">
                            <h3 class="font-bold mb-2 truncate">
                                <a href="index.php?page=campaign&id=<?php echo $campaign['id']; ?>" class="hover:text-blue-600">
                                    <?php echo $campaign['title']; ?>
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                <?php echo $campaign['description']; ?>
                            </p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo ($campaign['current_amount'] / $campaign['goal_amount']) * 100; ?>%"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>$<?php echo number_format($campaign['current_amount'], 2); ?> / $<?php echo number_format($campaign['goal_amount'], 2); ?></span>
                                <span><?php echo number_format(($campaign['current_amount'] / $campaign['goal_amount']) * 100, 0); ?>%</span>
                            </div>
                            <div class="mt-4">
                                <a href="index.php?page=campaign&id=<?php echo $campaign['id']; ?>" class="text-blue-600 hover:underline">Ver más</a>
                                <a href="index.php?page=donate&id=<?php echo $campaign['id']; ?>" class="float-right inline-block bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Donar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 p-6 rounded border text-center">
                <p class="text-gray-600 mb-4">No hay campañas recomendadas en este momento</p>
                <a href="index.php?page=explore_campaigns" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Explorar todas las campañas
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Impacto de donaciones -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Estudiantes apoyados -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Tu impacto</h2>
            
            <?php if(isset($donations) && !empty($donations)): ?>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="p-3 bg-blue-50 rounded">
                        <p class="text-2xl font-bold text-blue-600">
                            <?php 
                                // Calcular número único de estudiantes apoyados
                                $unique_students = [];
                                foreach($donations as $donation) {
                                    if(!in_array($donation['student_id'], $unique_students)) {
                                        $unique_students[] = $donation['student_id'];
                                    }
                                }
                                echo count($unique_students);
                            ?>
                        </p>
                        <p class="text-sm text-gray-600">Estudiantes apoyados</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded">
                        <p class="text-2xl font-bold text-green-600">
                            <?php 
                                // Calcular número de campañas financiadas completamente
                                $completed_campaigns = 0;
                                $campaign_ids = [];
                                foreach($donations as $donation) {
                                    if(!in_array($donation['campaign_id'], $campaign_ids)) {
                                        $campaign_ids[] = $donation['campaign_id'];
                                        if($donation['campaign_completed']) {
                                            $completed_campaigns++;
                                        }
                                    }
                                }
                                echo $completed_campaigns;
                            ?>
                        </p>
                        <p class="text-sm text-gray-600">Campañas completadas</p>
                    </div>
                </div>
                <p class="text-center mt-6 text-sm text-gray-600">
                    Gracias por tu generosidad. Tu apoyo está haciendo una diferencia real.
                </p>
            <?php else: ?>
                <div class="text-center py-6">
                    <p class="text-gray-600 mb-4">Aún no has realizado donaciones</p>
                    <p class="text-sm">Cuando dones, podrás ver el impacto de tu generosidad aquí.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Consejos para donantes -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Consejos para donantes</h2>
            
            <ul class="space-y-3 list-disc pl-5">
                <li class="text-gray-700">
                    <span class="font-semibold">Revisa las verificaciones:</span> 
                    Todos los estudiantes en EduFund pasan por un proceso de verificación.
                </li>
                <li class="text-gray-700">
                    <span class="font-semibold">Configura donaciones recurrentes:</span> 
                    Apoya a un estudiante de forma regular para ayudarle a lo largo de su educación.
                </li>
                <li class="text-gray-700">
                    <span class="font-semibold">Comparte campañas:</span> 
                    Si no puedes donar, compartir una campaña también ayuda mucho.
                </li>
                <li class="text-gray-700">
                    <span class="font-semibold">Consulta actualizaciones:</span> 
                    Los estudiantes comparten su progreso y cómo utilizan las donaciones.
                </li>
            </ul>
            
            <div class="mt-4 text-center">
                <a href="index.php?page=donation_guide" class="text-blue-600 hover:underline">
                    Ver guía completa para donantes
                </a>
            </div>
        </div>
    </div>
    
    <!-- Categorías populares -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-blue-600">Explora por categorías</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="index.php?page=explore_campaigns&category=technology" class="p-4 bg-blue-50 rounded-lg text-center hover:bg-blue-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-semibold">Tecnología</span>
            </a>
            <a href="index.php?page=explore_campaigns&category=books" class="p-4 bg-green-50 rounded-lg text-center hover:bg-green-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-green-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="font-semibold">Libros</span>
            </a>
            <a href="index.php?page=explore_campaigns&category=tuition" class="p-4 bg-yellow-50 rounded-lg text-center hover:bg-yellow-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-yellow-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">Matrículas</span>
            </a>
            <a href="index.php?page=explore_campaigns&category=supplies" class="p-4 bg-purple-50 rounded-lg text-center hover:bg-purple-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-purple-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <span class="font-semibold">Útiles escolares</span>
            </a>
        </div>
    </div>
</div>