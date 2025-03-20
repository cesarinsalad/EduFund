<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Iniciar sesión</h2>
            
            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
            
            <form id="login-form" method="post" action="api/users.php">
                <input type="hidden" name="action" value="login">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo electrónico
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" name="email" type="email" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password" name="password" type="password" required>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Iniciar sesión
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="register.php">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar el envío del formulario
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        // Enviar formulario vía AJAX
        $.ajax({
            type: 'POST',
            url: 'api/users.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redireccionar al dashboard después de inicio de sesión exitoso
                    window.location.href = 'index.php';
                } else {
                    $('#error-message').removeClass('hidden').text(response.message);
                }
            },
            error: function() {
                $('#error-message').removeClass('hidden').text('Ha ocurrido un error. Por favor intente nuevamente');
            }
        });
    });
});
</script>