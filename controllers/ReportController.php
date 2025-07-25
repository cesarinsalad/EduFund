<?php
require_once 'models/User.php';
require_once 'models/Campaign.php';
require_once 'models/Donation.php';
require_once 'models/StudentProfile.php';
require_once 'assets/libs/fpdf/fpdf.php';

// Definir la clase ReportPDF fuera de ReportController
class ReportPDF extends FPDF {
    // Variables de cabecera
    protected $report_title;
    protected $report_date;
    protected $report_period = '';
    protected $report_category = '';

    function fixEncoding($text) {
        return utf8_decode($text);
    }
    
    // Establecer variables de cabecera
    function setReportInfo($title, $period = '', $category = '') {
        $this->report_title = utf8_decode($title);
        $this->report_date = date('Y/m/d H:i:s');
        $this->report_period = utf8_decode($period);
        $this->report_category = utf8_decode($category);
    }
    
    // Cabecera de página
    function Header() {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Título
        $this->Cell(0, 10, 'EduFund - ' . $this->report_title, 0, 1, 'C');
        // Salto de línea
        $this->Ln(5);
        
        // Información del reporte
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, utf8_decode('Fecha de generación: ') . $this->report_date, 0, 1);
        if (!empty($this->report_period)) {
            $this->Cell(0, 6, utf8_decode('Período: ') . $this->report_period, 0, 1);
        }
        if (!empty($this->report_category)) {
            $this->Cell(0, 6, utf8_decode('Categoría: ') . $this->report_category, 0, 1);
        }
        
        $this->Ln(5);
    }
    
    // Pie de página
    function Footer() {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(0, 10, utf8_decode('Este reporte fue generado automáticamente por el sistema EduFund.'), 0, 0, 'C');
    }
    
    // Para crear una tabla de datos
    function GenerateTable($header, $data) {
        // Configuración de colores para el encabezado
        $this->SetFillColor(37, 99, 235); // Azul
        $this->SetTextColor(255); // Blanco
        $this->SetDrawColor(37, 99, 235); // Azul
        $this->SetLineWidth(0.3); // Grosor de línea
        $this->SetFont('Arial', 'B', 6); // Negrita, tamaño 9
    
        // Calcular el ancho de cada columna basado en el contenido más largo
        $maxWidth = 180; // Ancho máximo disponible para la tabla
        $colWidths = [];
        foreach ($header as $i => $col) {
            $maxLength = strlen($col); // Longitud del encabezado
            foreach ($data as $row) {
                $maxLength = max($maxLength, strlen($row[$i]));
            }
            $colWidths[$i] = min($maxWidth / count($header), $maxLength * 2); // Ajustar ancho máximo
        }
    
        // Imprimir encabezados
        foreach ($header as $i => $col) {
            $this->Cell($colWidths[$i], 7, utf8_decode($col), 1, 0, 'C', true);
        }
        $this->Ln(); // Salto de línea después del encabezado
    
        // Restaurar colores y fuente para los datos
        $this->SetFillColor(249, 250, 251); // Gris muy claro
        $this->SetTextColor(0); // Negro
        $this->SetFont('Arial', '', 6); // Reducir el tamaño de la fuente
    
        // Imprimir filas de datos
        $fill = false; // Para alternar colores
        foreach ($data as $row) {
            foreach ($row as $i => $col) {
                $x = $this->GetX();
                $y = $this->GetY();
                $this->MultiCell($colWidths[$i], 6, utf8_decode($col), 1, 'L', $fill);
                $this->SetXY($x + $colWidths[$i], $y); // Mover a la siguiente celda
            }
            $this->Ln(); // Salto de línea al final de cada fila
            $fill = !$fill; // Alternar colores
        }
    }
    
    // Verificar si hay que saltar de página
    function checkPageBreak($h) {
        if($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage();
        }
    }
}

class ReportController {
    private $db;
    private $userModel;
    private $campaignModel;
    private $donationModel;
    private $studentModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->campaignModel = new Campaign($db);
        $this->donationModel = new Donation($db);
        $this->studentModel = new StudentProfile($db);
    }
    
    /**
     * Muestra la página de generación de reportes
     */
    public function showReportGenerator() {
        // Obtener datos para los filtros del formulario
        $campaign_categories = $this->campaignModel->getCategories();
        
        include 'views/admin/reports/generator.php';
    }
    
    /**
     * Genera un archivo CSV con los datos del reporte
     */
    public function generateCSV($data, $filename) {
        if (ob_get_length()) ob_end_clean();
        // Configurar encabezados para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        // Crear output stream
        $output = fopen('php://output', 'w');
        
        // Agregar BOM para compatibilidad con Excel y caracteres UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados si hay datos
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            
            // Escribir datos
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        } else {
            // Si no hay datos, escribir mensaje
            fputcsv($output, ['No hay datos para el período seleccionado']);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Genera un archivo PDF con los datos del reporte
     */
    private function generatePDF($data, $filename, $report_type, $period = '', $category = '') {
        if (ob_get_length()) ob_end_clean();
        // Crear instancia de PDF
        $pdf = new ReportPDF();
        // Configurar información del reporte
        $report_title = '';
        switch ($report_type) {
            case 'donations':
                $report_title = 'Reporte de Donaciones';
                break;
            case 'campaigns':
                $report_title = 'Reporte de Campañas';
                break;
            case 'users':
                $report_title = 'Reporte de Usuarios';
                break;
            case 'verifications':
                $report_title = 'Reporte de Verificaciones';
                break;
        }
        
        $pdf->setReportInfo($report_title, $period, $category);
        
        // Metadatos del documento
        $pdf->SetTitle('EduFund - ' . $report_title);
        $pdf->SetAuthor('Sistema EduFund');
        $pdf->SetCreator('EduFund');
        
        $pdf->AliasNbPages(); // Para obtener el número total de páginas
        $pdf->AddPage();
        
        // Si no hay datos, mostrar mensaje
        if (empty($data)) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, utf8_decode('No hay datos para mostrar en el período seleccionado.'), 0, 1);
        } else {
            // Preparar encabezados y datos
            $headers = array();
            foreach (array_keys($data[0]) as $header) {
                $headers[] = $header;
            }
            
            // Crear tabla
            $pdf->GenerateTable($headers, array_map('array_values', $data));
            
            // Total de registros
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, 'Total de registros: ' . count($data), 0, 1);
        }
        
        if (headers_sent($file, $line)) {
            error_log("Encabezados ya enviados en $file en la línea $line");
        }
        // Salida
        $pdf->Output('D', $filename . '.pdf');
        exit;
    }

    public function generateReport() {
        ob_start();
        // Verificar si hay datos enviados
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: index.php?page=report_generator');
            exit;
        }
        
        // Obtener parámetros del formulario
        $report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';
        $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : '';
        $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $format = isset($_POST['format']) ? $_POST['format'] : 'csv';
        
        // Formatear fechas adecuadamente para consulta SQL (yyyy-mm-dd)
        if (!empty($date_start)) {
            $date_start = date('Y-m-d', strtotime($date_start));
        }
        if (!empty($date_end)) {
            $date_end = date('Y-m-d', strtotime($date_end));
        }
        
        // Validar parámetros
        if (!in_array($report_type, ['donations', 'campaigns', 'users', 'verifications'])) {
            $_SESSION['error'] = 'Tipo de reporte no válido';
            header('Location: index.php?page=report_generator');
            exit;
        }
        
        if (!in_array($format, ['csv', 'pdf'])) {
            $_SESSION['error'] = 'Formato no válido';
            header('Location: index.php?page=report_generator');
            exit;
        }
        
        // Obtener datos según el tipo de reporte
        $report_data = [];
        $filename = '';
        
        switch ($report_type) {
            case 'donations':
                $report_data = $this->donationModel->getReportData($date_start, $date_end);
                $filename = 'donaciones_' . date('Y-m-d');
                break;
                
            case 'campaigns':
                $report_data = $this->campaignModel->getReportData($date_start, $date_end, $category);
                $filename = 'campanas_' . date('Y-m-d');
                break;
                
            case 'users':
                $report_data = $this->userModel->getReportData($date_start, $date_end);
                $filename = 'usuarios_' . date('Y-m-d');
                break;
                
            case 'verifications':
                $report_data = $this->studentModel->getVerificationsReport($date_start, $date_end);
                $filename = 'verificaciones_' . date('Y-m-d');
                break;
        }
        
        // Depuración: Verificar los datos obtenidos
        error_log("Datos obtenidos para el reporte ($report_type): " . json_encode($report_data));
        
        // Generar el archivo en el formato solicitado
        if ($format == 'csv') {
            ob_end_clean();
            $this->generateCSV($report_data, $filename);
        } else {
            ob_end_clean();
            $period = '';
            if (!empty($date_start) && !empty($date_end)) {
                $period = date('d/m/Y', strtotime($date_start)) . ' - ' . date('d/m/Y', strtotime($date_end));
            } elseif (!empty($date_start)) {
                $period = 'Desde ' . date('d/m/Y', strtotime($date_start));
            } elseif (!empty($date_end)) {
                $period = 'Hasta ' . date('d/m/Y', strtotime($date_end));
            }
            
            $this->generatePDF($report_data, $filename, $report_type, $period, $category);
        }
    }
}
?>