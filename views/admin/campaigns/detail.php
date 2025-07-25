<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Detalle de Campaña</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6"><?php echo htmlspecialchars($campaign['title']); ?></h2>
        
        <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($campaign['full_name']); ?></p>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($campaign['description']); ?></p>
        <p><strong>Meta:</strong> $<?php echo number_format($campaign['goal_amount'], 2); ?></p>
        <p><strong>Fecha de Inicio:</strong> <?php echo date('d/m/Y', strtotime($campaign['start_date'])); ?></p>
        <p><strong>Fecha de Fin:</strong> <?php echo date('d/m/Y', strtotime($campaign['end_date'])); ?></p>
        
        <form action="index.php?page=process_campaign_verification" method="POST" class="mt-6">
            <input type="hidden" name="campaign_id" value="<?php echo $campaign['id']; ?>">
            
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="verified">Aprobar</option>
                <option value="rejected">Rechazar</option>
            </select>
            
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-4">Notas</label>
            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
            
            <button type="submit" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Procesar Verificación</button>
        </form>
    </div>
</div>