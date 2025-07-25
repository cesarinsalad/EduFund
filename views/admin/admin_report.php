<?php
$pageTitle = 'Generador de Reportes | Admin';
include_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Generador de Reportes</h1>
        <a href="index.php?page=admin_dashboard" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="index.php?page=generate_report" method="POST" class="space-y-6">
            <!-- Tipo de reporte -->
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Tipo de Reporte</label>
                <select id="report_type" name="report_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="donations">Donaciones</option>
                    <option value="campaigns">Campañas</option>
                    <option value="users">Usuarios</option>
                </select>
            </div>

            <!-- Rango de fechas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Fecha de inicio</label>
                    <input type="date" id="date_start" name="date_start" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Fecha de fin</label>
                    <input type="date" id="date_end" name="date_end" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- Formato de salida -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Formato de salida</label>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <input id="format_csv" name="format" type="radio" value="csv" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                        <label for="format_csv" class="ml-2 block text-sm text-gray-700 dark:text-gray-200">
                            CSV
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="format_pdf" name="format" type="radio" value="pdf" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                        <label for="format_pdf" class="ml-2 block text-sm text-gray-700 dark:text-gray-200">
                            PDF
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Generar Reporte
            </button>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>