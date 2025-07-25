<?php
$pageTitle = 'Generador de Reportes';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Generador de Reportes</h1>
        <a href="index.php?page=admin_dashboard" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Dashboard
        </a>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">Configurar Reporte</h2>
        
        <form action="index.php?page=generate_report" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de reporte -->
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Reporte</label>
                    <select id="report_type" name="report_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="donations">Donaciones</option>
                        <option value="campaigns">Campañas</option>
                        <option value="users">Usuarios</option>
                        <option value="verifications">Verificaciones</option>
                    </select>
                </div>
                
                <!-- Formato -->
                <div>
                    <label for="format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formato de Salida</label>
                    <select id="format" name="format" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="csv">CSV (Excel)</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                
                <!-- Rango de fechas -->
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
                    <input type="date" id="date_start" name="date_start" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Fin</label>
                    <input type="date" id="date_end" name="date_end" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <!-- Categoría (solo para campañas) -->
                <div id="category_container" style="display: none;">
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
                    <select id="category" name="category" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Todas las categorías</option>
                        <?php if(isset($campaign_categories)): ?>
                            <?php foreach($campaign_categories as $category): ?>
                                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar u ocultar el campo de categoría según el tipo de reporte
    const reportTypeSelect = document.getElementById('report_type');
    const categoryContainer = document.getElementById('category_container');
    
    reportTypeSelect.addEventListener('change', function() {
        if (this.value === 'campaigns') {
            categoryContainer.style.display = 'block';
        } else {
            categoryContainer.style.display = 'none';
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>