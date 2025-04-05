<?php
// Título de la página
$pageTitle = "Crear una Campaña | EduFund";
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-700 shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Crear una Nueva Campaña</h1>
        
        <?php if(isset($errors) && !empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc pl-5">
                    <?php foreach($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título de la Campaña *</label>
                <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">Un título claro y atractivo para tu campaña (máx. 100 caracteres)</p>
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoría *</label>
                <select id="category" name="category" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">Selecciona una categoría</option>
                    <option value="Licenciatura" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Licenciatura') ? 'selected' : ''; ?>>Licenciatura</option>
                    <option value="Maestría" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Maestría') ? 'selected' : ''; ?>>Maestría</option>
                    <option value="Doctorado" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Doctorado') ? 'selected' : ''; ?>>Doctorado</option>
                    <option value="Diplomado" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Diplomado') ? 'selected' : ''; ?>>Diplomado</option>
                    <option value="Material Educativo" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Material Educativo') ? 'selected' : ''; ?>>Material Educativo</option>
                    <option value="Otros" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
                </select>
            </div>
            
            <div>
                <label for="goal_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Objetivo (MXN) *</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" id="goal_amount" name="goal_amount" min="1" step="0.01"
                           value="<?php echo isset($_POST['goal_amount']) ? htmlspecialchars($_POST['goal_amount']) : ''; ?>"
                           class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                <p class="text-xs text-gray-500 mt-1">¿Cuánto dinero necesitas recaudar?</p>
            </div>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio *</label>
                    <input type="date" id="start_date" name="start_date" 
                           value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : date('Y-m-d'); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Finalización *</label>
                    <input type="date" id="end_date" name="end_date" 
                           value="<?php echo isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : date('Y-m-d', strtotime('+30 days')); ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción *</label>
                <textarea id="description" name="description" rows="6"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Explica detalladamente tu situación, necesidades y objetivos</p>
            </div>
            
            <div>
                <label for="campaign_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagen de la Campaña</label>
                <input type="file" id="campaign_image" name="campaign_image" accept="image/png, image/jpeg, image/jpg"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Imagen JPG, JPEG o PNG (máx. 2MB)</p>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Crear Campaña
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>