<?php
require_once 'controllers/AuthController.php';


// Verifica si el formulario de cierre de sesión fue enviado
if (isset($_POST['logout_button'])) {
  // Instancia la clase AuthController
  $authController = new AuthController();

  // Llama a la función logout
  $authController->logout();
}

// Determinar el nombre a mostrar (preferir nombre completo, si no, username)
$display_name = isset($_SESSION['full_name']) && !empty($_SESSION['full_name'])
                ? htmlspecialchars($_SESSION['full_name'])
                : (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Usuario');

// Determinar la imagen de perfil (usar la de sesión o el default si no existe)
$profile_picture_url = isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])
                       ? htmlspecialchars($_SESSION['profile_picture'])
                       : '../assets/img/default-avatar.svg'; // Asegúrate que esta ruta por defecto exista

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFund - Plataforma de Crowdfunding Educativo</title>

    <!-- Tailwind CSS (CDN para desarrollo) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-800">

<header class="bg-white dark:bg-gray-900 relative shadow-md">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <!-- Logo -->
      <div class="flex-shrink-0">
        <a class="block text-blue-600 dark:text-blue-300" href="../index.php">
          <span class="sr-only">Inicio</span>
          <img src="assets/img/logo-edufund.svg" alt="EduFund Logo" class="h-12 md:h-16">
        </a>
      </div>

      <!-- Navegación y Autenticación/Usuario -->
      <div class="flex items-center gap-4 md:gap-6">
        <!-- Navegación principal (Desktop) -->
        <nav aria-label="Global" class="hidden lg:block">
          <ul class="flex items-center gap-6 md:gap-8 text-sm whitespace-nowrap">
            <li><a class="text-gray-500 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="index.php?page=donar_ahora">Donar Ahora</a></li>
            <li><a class="text-gray-500 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="index.php?page=home-campaign">Crear Campaña</a></li>
            <li><a class="text-gray-500 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="index.php?page=nosotros">Nosotros</a></li>
            <li><a class="text-gray-500 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="index.php?page=faq">Preguntas Frecuentes</a></li>
          </ul>
        </nav>

        <!-- Sección de Autenticación (Visitante) o Usuario (Logueado) - Desktop -->
        <div class="hidden lg:flex items-center gap-3">
          <?php if (!isset($_SESSION['user_id'])) : ?>
            <!-- Usuario NO Logueado: Botones Iniciar Sesión / Registrarse -->
            <a
              class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 dark:hover:bg-blue-500 whitespace-nowrap transition"
              href="./login.php"
            >
              Iniciar Sesión
            </a>
            <a
              class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 whitespace-nowrap transition"
              href="../register.php"
            >
              Registrarse
            </a>
          <?php else : ?>
            <!-- Usuario Logueado: Info de Perfil y Logout -->
            <!-- modo pc -->
            <div class="relative">
              <img
                src="<?php echo $profile_picture_url; ?>"
                alt="Foto de perfil"
                class="cursor-pointer profile-img rounded-full border border-gray-200 dark:border-gray-700 h-10 w-10"
                id="profile-button"
                onerror="this.onerror=null; this.src='../assets/img/default-avatar.svg';"
              />
              <div
                id="profile-dropdown"
                class="hidden absolute right-0 mt-2 w-56 rounded-md border border-gray-200 bg-white shadow-lg dark:bg-gray-900 dark:border-gray-700 z-50">
                <div class="p-2">
                  <p class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300"><?php echo $display_name; ?></p>
                  <a href="<?php 
    if (isset($_SESSION['role'])) {
        switch ($_SESSION['role']) {
            case 'admin':
                echo 'index.php?page=admin_dashboard';
                break;
            case 'student':
                echo 'index.php?page=student_dashboard';
                break;
            case 'donor':
                echo 'index.php?page=donor_dashboard';
                break;
            default:
                echo 'index.php';
        }
    } else {
        echo 'index.php'; 
    }
?>" class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Mi perfil</a>
                  <form method="post">
                    <button
                      type="submit"
                      name="logout_button"
                      class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50">
                      Cerrar Sesión
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Botón del menú móvil -->
        <div class="lg:hidden">
          <button
            id="mobile-menu-button"
            class="rounded-sm bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700"
            aria-controls="mobile-menu"
            aria-expanded="false"
          >
            <span class="sr-only">Abrir menú principal</span>
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Menú Móvil Desplegable -->
  <div
    id="mobile-menu"
    class="hidden lg:hidden absolute top-full left-0 w-full bg-white dark:bg-gray-900 shadow-lg z-50 border-t border-gray-200 dark:border-gray-700"
  >
    <!-- Navegación Móvil -->
    <nav aria-label="Mobile Navigation" class="px-4 pt-4 pb-4">
      <ul class="flex flex-col space-y-4 text-sm">
         <li><a class="block text-gray-600 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="#">Donar Ahora</a></li>
         <li><a class="block text-gray-600 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="#">Crear Campaña</a></li>
         <li><a class="block text-gray-600 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="#">Nosotros</a></li>
         <li><a class="block text-gray-600 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-300" href="#">Preguntas Frecuentes</a></li>
      </ul>
    </nav>

    <!-- Sección de Autenticación (Visitante) o Usuario (Logueado) - Móvil -->
    <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700 pt-4">
      <?php if (!isset($_SESSION['user_id'])) : ?>
        <!-- Usuario NO Logueado: Botones Iniciar Sesión / Registrarse -->
        <div class="flex flex-col space-y-3">
            <a class="block w-full text-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 dark:hover:bg-blue-500 transition" href="./login.php">
                Iniciar Sesión
            </a>
            <a class="block w-full text-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition" href="../register.php">
                Registrarse
            </a>
        </div>
      <?php else : ?>
        <!-- Usuario Logueado: Info de Perfil y Logout -->

        <!-- bloque del dropdown para telefono-->
        <div class="relative inline-block">
          <img
            src="<?php echo $profile_picture_url; ?>"
            alt="Foto de perfil"
            class="cursor-pointer profile-img rounded-full border border-gray-200 dark:border-gray-700 h-10 w-10"
            id="mobile-profile-button"
            onerror="this.onerror=null; this.src='../assets/img/default-avatar.svg';"
          />

          <div
            id="mobile-profile-dropdown"
            class="hidden absolute left-0 mt-2 w-56 rounded-md border border-gray-200 bg-white shadow-lg dark:bg-gray-900 dark:border-gray-700 z-50"
            <div class="p-2">
              <p class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300"><?php echo $display_name; ?></p>
              <a href="#" class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Mi perfil</a>
              <a href="#" class="block rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Mis campañas</a>
              <form method="post">
                <button
                  type="submit"
                  name="logout_button"
                  class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/50"
                >
                  Cerrar Sesión
                </button>
              </form>
            </div>
          </div>
        </div>


      <?php endif; ?>
    </div>
  </div>
</header>

<!-- JavaScript para el menú móvil -->
<script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
        const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
        mobileMenu.classList.toggle('hidden');
        menuButton.setAttribute('aria-expanded', !isExpanded);
    });

    document.addEventListener('click', (event) => {
        const isClickInsideMenu = mobileMenu.contains(event.target);
        const isClickOnButton = menuButton.contains(event.target);
        if (!isClickInsideMenu && !isClickOnButton && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            menuButton.setAttribute('aria-expanded', 'false');
        }
    });

    //script del dropdown
    // para pc 
    document.addEventListener("DOMContentLoaded", function () {
    const profileButton = document.getElementById("profile-button");
    const profileDropdown = document.getElementById("profile-dropdown");

    if (profileButton && profileDropdown) {
      profileButton.addEventListener("click", function (e) {
        e.stopPropagation();
        profileDropdown.classList.toggle("hidden");
      });

      document.addEventListener("click", function (e) {
        if (!profileDropdown.contains(e.target) && !profileButton.contains(e.target)) {
          profileDropdown.classList.add("hidden");
        }
      });
    }

    // literalmente el mismo codigo pero con mobileProfileButton
    const mobileProfileButton = document.getElementById("mobile-profile-button");
    const mobileProfileDropdown = document.getElementById("mobile-profile-dropdown");

    if (mobileProfileButton && mobileProfileDropdown) {
      mobileProfileButton.addEventListener("click", function (e) {
        e.stopPropagation();
        mobileProfileDropdown.classList.toggle("hidden");
      });
      document.addEventListener("click", function (e) {
        if (!mobileProfileDropdown.contains(e.target) && !mobileProfileButton.contains(e.target)) {
          mobileProfileDropdown.classList.add("hidden");
        }
      });
    }
  });
</script>



</body>
</html>