<?php
// filepath: c:\laragon\www\edufund\views\donations\donation_form.php
$pageTitle = "Realizar Donación | EduFund";
include_once 'includes/header.php';

// Calcular porcentaje de avance
$percentage = ($campaign['goal_amount'] > 0) ? min(100, round(($campaign['current_amount'] / $campaign['goal_amount']) * 100)) : 0;
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <!-- Información de la campaña -->
                <div class="md:w-1/2 p-6 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Resumen de la Campaña</h2>
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><?php echo htmlspecialchars($campaign['title']); ?></h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Por <?php echo isset($campaign['full_name']) ? htmlspecialchars($campaign['full_name']) : 'Estudiante desconocido'; ?>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-600">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-300">$<?php echo number_format($campaign['current_amount'], 2); ?> recaudados</span>
                            <span class="text-gray-600 dark:text-gray-400"><?php echo $percentage; ?>% de $<?php echo number_format($campaign['goal_amount'], 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        <p>Tu donación ayudará a <?php echo htmlspecialchars($campaign['full_name']); ?> a alcanzar su meta educativa.</p>
                    </div>
                    
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <p class="mb-2"><i class="fas fa-lock mr-1"></i> Procesamiento seguro a través de PayPal</p>
                        <p><i class="fas fa-globe mr-1"></i> Todas las donaciones están sujetas a nuestros <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Términos y Condiciones</a></p>
                    </div>
                </div>
                
                <!-- Formulario de donación -->
                <div class="md:w-1/2 p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Detalles de la Donación</h2>
                    
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
                    
                    <form method="POST" action="index.php?page=process_donation">
                        <input type="hidden" name="campaign_id" value="<?php echo $campaign['id']; ?>">
                        
                        <!-- Monto de la donación -->
                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monto de la donación (USD) *</label>
                            
                            <div class="grid grid-cols-4 gap-2 mb-2">
                                <button type="button" class="donation-amount-btn px-4 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" data-amount="1">$1</button>
                                <button type="button" class="donation-amount-btn px-4 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" data-amount="5">$5</button>
                                <button type="button" class="donation-amount-btn px-4 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" data-amount="20">$20</button>
                                <button type="button" class="donation-amount-btn px-4 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" data-amount="50">$50</button>
                            </div>
                        
                            <div class="mt-3 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" id="amount" name="amount" min="1" step="1" 
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : '1'; ?>" required> 
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Monto mínimo de donación: $1 USD</p>
                        </div>
                        
                        <!-- Información del donante (para no registrados) -->
                        <?php if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor'): ?>
                            <div class="mb-6">
                                <label for="donor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tu nombre *</label>
                                <input type="text" id="donor_name" name="donor_name" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="<?php echo isset($_POST['donor_name']) ? htmlspecialchars($_POST['donor_name']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-6">
                                <label for="donor_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tu correo electrónico *</label>
                                <input type="email" id="donor_email" name="donor_email" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="<?php echo isset($_POST['donor_email']) ? htmlspecialchars($_POST['donor_email']) : ''; ?>" required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Solo para recibir confirmación de tu donación</p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Mensaje (opcional) -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mensaje (opcional)</label>
                            <textarea id="message" name="message" rows="3" 
                                     class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tu mensaje aparecerá en la página de la campaña</p>
                        </div>
                        
                        <!-- Donación anónima -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="anonymous" name="anonymous" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                       <?php echo isset($_POST['anonymous']) && $_POST['anonymous'] ? 'checked' : ''; ?>>
                                <label for="anonymous" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Hacer mi donación anónima
                                </label>
                            </div>
                        </div>
                        
                        <!-- Botón de donación -->
                        <div class="mt-8">
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                </svg>
                                Continuar con PayPal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar botones de monto predefinido
    const amountButtons = document.querySelectorAll('.donation-amount-btn');
    const amountInput = document.getElementById('amount');
    
    // Agregar listeners a los botones
    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Establecer el valor en el input
            amountInput.value = this.getAttribute('data-amount');
            
            // Remover clase activa de todos los botones
            amountButtons.forEach(btn => {
                btn.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-700');
                btn.classList.add('bg-white', 'dark:bg-gray-700');
            });
            
            // Agregar clase activa al botón seleccionado
            this.classList.remove('bg-white', 'dark:bg-gray-700');
            this.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
        });
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>