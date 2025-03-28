<div class="container bg-fixed bg-[url('../assets/img/estudiantes-2-blur.jpg')] bg-cover bg-center bg-no-repeat mx-auto px-4 py-8 bg-stone-100 dark:bg-gray-900 pt-32 pb-32">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6 dark:text-white">Iniciar sesión</h2>
            
            <?php if(isset($error_message) && !empty($error_message)): ?>
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['registration_success'])): ?>
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['message']; ?>
                    <?php unset($_SESSION['registration_success']); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <form id="login-form" method="post" action="index.php?page=login_process">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                        Correo electrónico
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" name="email" type="email" required>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline" 
                           id="password" name="password" type="password" required>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Recordarme
                        </label>
                    </div>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" href="index.php?page=forgot_password">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Iniciar sesión
                    </button>
                    <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" href="index.php?page=register">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>