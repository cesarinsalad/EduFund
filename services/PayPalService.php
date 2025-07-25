<?php
require_once 'config/paypal.php';

class PayPalService {
    private $config;
    private $accessToken;
    
    public function __construct() {
        $this->config = new PayPalConfig();
    }
    
    // Obtener token de acceso para la API de PayPal.
    private function getAccessToken() {
        if($this->accessToken) {
            return $this->accessToken;
        }
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl() . "/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $this->config->getClientId() . ":" . $this->config->getSecret());
        
        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Accept-Language: en_US";
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception("Error al obtener token: " . curl_error($ch));
        }
        
        curl_close($ch);
        
        $response = json_decode($result, true);
        
        // Registrar la respuesta para depuración
        error_log("Respuesta de PayPal al solicitar token: " . json_encode($response));
        
        if (!isset($response['access_token'])) {
            $error_message = isset($response['error_description']) ? $response['error_description'] : 'Error desconocido';
            throw new Exception("Error al obtener el token de acceso: " . $error_message);
        }
        
        $this->accessToken = $response['access_token'];
        return $this->accessToken;
    }
    

    // Crear una orden de pago en PayPal.
    public function createOrder($amount, $campaign_title, $return_url, $cancel_url) {
        $accessToken = $this->getAccessToken();
        
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amount, 2, '.', '')
                    ],
                    'description' => 'Donación para: ' . $campaign_title
                ]
            ],
            'application_context' => [
                'brand_name' => 'EduFund',
                'landing_page' => 'BILLING',
                'user_action' => 'PAY_NOW',
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
                'shipping_preference' => 'NO_SHIPPING'
            ]
        ];
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl() . "/v2/checkout/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Error al crear orden: " . curl_error($ch));
        }
        
        $response = json_decode($result, true);
        
        // Registrar la respuesta para depuración
        error_log("Respuesta de PayPal al crear orden: " . json_encode($response));
        
        if (!isset($response['id'])) {
            $error_message = isset($response['message']) ? $response['message'] : 'Error desconocido';
            throw new Exception("Error al crear la orden en PayPal: " . $error_message);
        }
        
        curl_close($ch);
        return $response;
    }
    
    // Capturar el pago de una orden.
    public function capturePayment($orderId) {
        $accessToken = $this->getAccessToken();
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl() . "/v2/checkout/orders/" . $orderId . "/capture");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
        
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception("Error al capturar pago: " . curl_error($ch));
        }
        
        curl_close($ch);
        
        return json_decode($result, true);
    }
    
    // Verificar el estado de una orden.    
    public function checkOrderStatus($orderId) {
        $accessToken = $this->getAccessToken();
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl() . "/v2/checkout/orders/" . $orderId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception("Error al verificar orden: " . curl_error($ch));
        }
        
        curl_close($ch);
        
        return json_decode($result, true);
    }
}
?>