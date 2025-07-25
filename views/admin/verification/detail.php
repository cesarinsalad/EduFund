<?php 
$pageTitle = 'Verificación de Estudiante | Admin';
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Verificación de Estudiante</h1>
        <a href="index.php?page=admin_dashboard" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Información del Estudiante -->
        <div class="md:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Información del Estudiante</h2>
                
                <div class="mb-6 flex items-center">
                    <?php if (!empty($verification['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($verification['profile_picture']); ?>" alt="Foto de perfil" class="w-16 h-16 rounded-full mr-4 object-cover">
                    <?php else: ?>
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-blue-500 font-bold text-xl"><?php echo strtoupper(substr($verification['full_name'] ?? 'U', 0, 1)); ?></span>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><?php echo htmlspecialchars($verification['full_name'] ?? 'Sin nombre'); ?></h3>
                        <p class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($verification['email'] ?? 'Sin email'); ?></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Institución</p>
                        <p class="font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($verification['institution'] ?? 'No especificado'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nivel Educativo</p>
                        <p class="font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($verification['educational_level'] ?? 'No especificado'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Carrera/Programa</p>
                        <p class="font-medium text-gray-800 dark:text-white"><?php echo htmlspecialchars($verification['major'] ?? 'No especificado'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Fecha de Registro</p>
                        <p class="font-medium text-gray-800 dark:text-white"><?php echo date('d/m/Y', strtotime($verification['created_at'])); ?></p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Documentos Adjuntos</p>
                    <?php if (!empty($verification['document_path'])): ?>
                        <div class="mt-2">
                            <a href="<?php echo htmlspecialchars($verification['document_path']); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                Ver Documento de Identidad Estudiantil
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="font-medium text-red-500">No hay documentos adjuntos</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Formulario de Verificación -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Procesar Verificación</h2>
                
                <form action="index.php?page=process_verification" method="POST">
                    <input type="hidden" name="verification_id" value="<?php echo $verification['id']; ?>">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="status">
                            Estado
                        </label>
                        <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="verified">Aprobado</option>
                            <option value="rejected">Rechazado</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="notes">
                            Notas
                        </label>
                        <textarea id="notes" name="notes" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Notas adicionales sobre esta verificación..."></textarea>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Procesar Verificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>