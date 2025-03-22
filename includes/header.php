<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFund - Plataforma de Crowdfunding Educativo</title>
    
    <!-- Tailwind CSS (CDN para desarrollo) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<header class="bg-white dark:bg-gray-900">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <!-- Logo - siempre visible -->
      <div class="flex items-center">
        <a class="block text-blue-600 dark:text-blue-300" href="#">
          <span class="sr-only">Inicio</span>
          <img src="assets/img/logo-edufund.svg" alt="EduFund Logo" class="h-16">
        </a>
      </div>

      <!-- Navegación y botones de autenticación -->
      <div class="flex items-center gap-6">
        <!-- Navegación principal - oculto en móvil -->
        <nav aria-label="Global" class="hidden lg:block">
          <ul class="flex items-center gap-8 text-sm whitespace-nowrap">
            <li>
              <a
                class="text-gray-500 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-300"
                href="#"
              >
                Donar Ahora
              </a>
            </li>

            <li>
              <a
                class="text-gray-500 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-300"
                href="#"
              >
                Crear Campaña
              </a>
            </li>

            <li>
              <a
                class="text-gray-500 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-300"
                href="#"
              >
                Nosotros
              </a>
            </li>

            <li>
              <a
                class="text-gray-500 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-300"
                href="#"
              >
                Preguntas Frecuentes
              </a>
            </li>
          </ul>
        </nav>

        <!-- Botones de autenticación -->
        <div class="flex items-center gap-3">
          <div class="hidden md:flex md:gap-3">
            <a
              class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 dark:hover:bg-blue-500 whitespace-nowrap"
              href="#"
            >
              Iniciar Sesión
            </a>

            <a
              class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 whitespace-nowrap"
              href="#"
            >
              Registrarse
            </a>
          </div>

          <!-- Botón del menú móvil -->
          <button
            class="rounded-sm bg-gray-100 p-2 text-gray-600 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 lg:hidden"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</header>
