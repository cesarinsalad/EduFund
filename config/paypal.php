<?php

class PayPalConfig {
    // 'sandbox' para pruebas, 'live' para producción.
    private $mode = 'sandbox';
    
    // URLs de la API de PayPal.
    private $sandbox_api_url = 'https://api-m.sandbox.paypal.com';
    private $live_api_url = 'https://api-m.paypal.com';
    
    public function getClientId() {
        return $this->mode === 'sandbox' 
            ? 'AdI8qMnal0EN7otx0WzMlFsjwJxMhW1MPivr48deZcEzq4HEsOugSoACyc65NPvTGvKCX47QOUuRIDji' 
            : 'TU_CLIENT_ID_DE_PRODUCCION';
    }
    
    public function getSecret() { 
        return $this->mode === 'sandbox' 
            ? 'ECD_e1gcs1u5YZAIxfvwtP0Pt2vvWiQXBQeqtGiacsMvdHKSmDTfT3d9P008Lj4BUb3yU1Uwm9GNphPJ' 
            : 'TU_SECRET_DE_PRODUCCION';
    }
    
    // Obtener la URL de la API según el modo configurado.
    public function getApiUrl() { 
        return $this->mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }
    
    // Corroborar que el modo actual sea sandbox, no live.
    public function isSandbox() {
        return $this->mode === 'sandbox';
    }
}
?>