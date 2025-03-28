<head>
    <script src="../../assets/libs/jquery/jquery.min.js"></script>
</head>
    <div class="container bg-fixed bg-[url('../assets/img/estudiantes-2-blur.jpg')] bg-cover bg-center bg-no-repeat mx-auto px-4 py-8 bg-stone-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6 dark:text-white">Crear una cuenta</h2>
            
            <?php if(isset($error_message) && !empty($error_message)): ?>
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="flex border-b dark:border-gray-700">
                <button id="btn-donor" type="button" class="role-tab py-2 px-4 font-bold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 flex-1 text-center">Quiero Donar</button>
                <button id="btn-student" type="button" class="role-tab py-2 px-4 font-bold text-gray-500 dark:text-gray-400 flex-1 text-center">Necesito Apoyo</button>
            </div>
            
            <form id="register-form" method="post" action="index.php?page=register_process" enctype="multipart/form-data">
                <input type="hidden" name="role" id="role" value="donor">
                
                <!-- Campos comunes -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 mt-4" for="username">
                        Nombre de Usuario
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="username" name="username" type="text" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                        Nombre Completo
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="full_name" name="full_name" type="text" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                        Correo Electrónico
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" name="email" type="email" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password" name="password" type="password" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="confirm_password">
                        Confirmar Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="confirm_password" name="confirm_password" type="password" required>
                </div>
                
                <!-- Campos para donantes -->
                <div id="donor-fields">
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="display_name">
                            Nombre para Mostrar
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                               id="display_name" name="display_name" type="text">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Se mostrará al realizar donaciones. Deja en blanco para usar tu nombre de usuario</p>
                    </div>
                </div>
                
                <!-- Campos para estudiantes -->
                <div id="student-fields" class="hidden">
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="institution">
                            Institución Educativa
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                               id="institution" name="institution" type="text">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="educational_level">
                            Nivel Educativo
                        </label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline"
                                id="educational_level" name="educational_level">
                            <option value="">Selecciona...</option>
                            <option value="high_school">Bachillerato</option>
                            <option value="university">Universidad</option>
                            <option value="postgraduate">Posgrado</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="document_number">
                            Número de Documento Institucional
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                               id="document_number" name="document_number" type="text">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="verification_documents">
                            Documento de Verificación
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                               id="verification_documents" type="file" name="verification_documents">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por favor sube un documento que acredite tu estatus de estudiante (PDF)</p>
                    </div>
                    
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Acepto los <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">términos y condiciones</a> y la <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">política de privacidad</a>
                        </label>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Registrarse
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" href="index.php?page=login">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Estado inicial correcto
    const initRole = 'donor'; 
    $(`#btn-${initRole}`).trigger('click');

    // Manejo de pestañas
    $('.role-tab').click(function(e) {
        e.preventDefault();
        
        // Limpiar estilos para modo claro y oscuro
        $('.role-tab').removeClass('text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400')
                      .addClass('text-gray-500 dark:text-gray-400');
        
        // Aplicar estilos al botón clickeado para modo claro y oscuro
        $(this).removeClass('text-gray-500 dark:text-gray-400')
               .addClass('text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400');
        
        // Mostrar/ocultar campos
        const isDonor = $(this).attr('id') === 'btn-donor';
        $('#role').val(isDonor ? 'donor' : 'student');
        $('#donor-fields').toggle(isDonor);
        $('#student-fields').toggle(!isDonor);
    });

    // Validación mejorada
    $('#register-form').submit(function(e) {
        // Validación de contraseña
        if ($('#password').val() !== $('#confirm_password').val()) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        // Validación para estudiantes
        if ($('#role').val() === 'student') {
            const requiredFields = [
                '#institution', 
                '#educational_level',
                '#document_number'
            ];
            
            // Verificar campos vacíos
            let missingFields = requiredFields.filter(field => !$(field).val().trim());
            
            // Validar archivo (método especial para inputs de tipo file)
            const fileInput = $('#verification_documents')[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                missingFields.push('#verification_documents');
            }

            if (missingFields.length > 0) {
                alert('Por favor completa todos los campos requeridos para estudiantes');
                return false;
            }
        }
        return true;
    });
});
</script>