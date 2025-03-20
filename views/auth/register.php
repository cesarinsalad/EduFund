<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Crear una cuenta</h2>
            
            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
            <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>
            
            <form id="register-form" method="post" action="api/users.php">
                <input type="hidden" name="action" value="register">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nombre completo
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="name" name="name" type="text" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo electrónico
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" name="email" type="email" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password" name="password" type="password" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                        Confirmar contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="confirm_password" name="confirm_password" type="password" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tipo de usuario
                    </label>
                    <div class="flex items-center">
                        <input type="radio" id="donor" name="user_type" value="donor" checked class="mr-2">
                        <label for="donor" class="mr-4">Donante</label>
                        
                        <input type="radio" id="student" name="user_type" value="student" class="mr-2">
                        <label for="student">Estudiante</label>
                    </div>
                </div>
                
                <div id="student-fields" class="hidden">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="institution">
                            Institución educativa
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="institution" name="institution" type="text">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="study_program">
                            Programa de estudio
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="study_program" name="study_program" type="text">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">
                            ID de estudiante
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="student_id" name="student_id" type="text">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Registrarse
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="login.php">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Mostrar/ocultar campos de estudiante
    $('input[name="user_type"]').change(function() {
        if ($(this).val() === 'student') {
            $('#student-fields').removeClass('hidden');
        } else {
            $('#student-fields').addClass('hidden');
        }
    });
    
    // Manejar el envío del formulario
    $('#register-form').submit(function(e) {
        e.preventDefault();
        
        // Validar que las contraseñas coincidan
        if ($('#password').val() !== $('#confirm_password').val()) {
            $('#error-message').removeClass('hidden').text('Las contraseñas no coinciden');
            return false;
        }
        
        // Validar campos de estudiante si ese tipo está seleccionado
        if ($('input[name="user_type"]:checked').val() === 'student') {
            if ($('#institution').val() === '' || $('#study_program').val() === '' || $('#student_id').val() === '') {
                $('#error-message').removeClass('hidden').text('Por favor complete todos los campos del perfil de estudiante');
                return false;
            }
        }
        
        // Enviar formulario vía AJAX
        $.ajax({
            type: 'POST',
            url: 'api/users.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#success-message').removeClass('hidden').text(response.message);
                    $('#error-message').addClass('hidden');
                    
                    // Redireccionar después de registro exitoso
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    $('#error-message').removeClass('hidden').text(response.message);
                    $('#success-message').addClass('hidden');
                }
            },
            error: function() {
                $('#error-message').removeClass('hidden').text('Ha ocurrido un error. Por favor intente nuevamente');
                $('#success-message').addClass('hidden');
            }
        });
    });
});
</script>