<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFund - Plataforma de Crowdfunding Educativo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN para desarrollo) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="bg-blue-600 text-white">
        <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="logo">
                <a href="index.php" class="text-2xl font-bold">EduFund</a>
            </div>
            <div class="navigation">
    <ul class="flex space-x-4">
        <li><a href="index.php" class="hover:text-blue-200">Inicio</a></li>
        <li><a href="#" class="hover:text-blue-200">Explorar campañas</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="#" class="hover:text-blue-200">Crear campaña</a></li>
            <li class="ml-4">
                <div class="dropdown">
                    <button class="hover:text-blue-200 flex items-center">
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?> <i class="fas fa-caret-down ml-1"></i>
                    </button>
                    <div class="dropdown-menu absolute bg-white shadow-lg rounded mt-2 py-2 w-48 hidden">
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <a href="admin/dashboard.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Panel Admin</a>
                        <?php else: ?>
                            <a href="user/profile.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Mi perfil</a>
                            <?php if ($_SESSION['user_type'] === 'student'): ?>
                                <a href="user/campaigns.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Mis campañas</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="border-t border-gray-200 my-1"></div>
                        <a href="#" id="logout-link" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Cerrar sesión</a>
                    </div>
                </div>
            </li>
        <?php else: ?>
            <li><a href="register.php" class="hover:text-blue-200">Registrarse</a></li>
            <li><a href="login.php" class="hover:text-blue-200">Iniciar sesión</a></li>
        <?php endif; ?>
    </ul>
</div>
        </nav>
    </header>
    <main class="container mx-auto px-4 py-6">
    <script>
$(document).ready(function() {
    // Toggle dropdown menu
    $('.dropdown button').click(function() {
        $(this).next('.dropdown-menu').toggleClass('hidden');
    });
    
    // Cierre de sesión
    $('#logout-link').click(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'api/users.php',
            data: {action: 'logout'},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = 'index.php';
                }
            }
        });
    });
    
    // Cerrar dropdown al hacer clic fuera
    $(document).click(function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').addClass('hidden');
        }
    });
});
</script>