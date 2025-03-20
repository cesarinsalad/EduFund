# EduFund - Plataforma de Crowdfunding Educativo

EduFund es una plataforma web de crowdfunding similar a GoFundMe, pero especializada en recaudar fondos para estudiantes que necesitan apoyo financiero para sus estudios, equipos tecnológicos, útiles escolares y recursos educativos.

## Tecnologías utilizadas

- HTML, CSS, JavaScript
- PHP
- MySQL
- AdminLTE
- Bootstrap
- Tailwind CSS
- jQuery
- AJAX
- DataTables
- API REST

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)

## Configuración del entorno de desarrollo

1. Clone este repositorio en el directorio `www` de Laragon
2. Cree una base de datos llamada `edufund` en phpMyAdmin
3. Importe la estructura de la base de datos desde `database/structure.sql`
4. Configure la URL local como `edufund.test` en Laragon
5. Abra el navegador en `http://edufund.test`

## Estructura del proyecto

- `assets/`: Archivos CSS, JavaScript e imágenes
- `includes/`: Componentes reutilizables (header, footer, etc.)
- `api/`: Endpoints para AJAX
- `views/`: Archivos de interfaz de usuario
- `config/`: Archivos de configuración
- `uploads/`: Archivos subidos por los usuarios

## Características principales

1. Perfiles de estudiantes verificados
2. Sistema de donaciones segmentadas
3. Transparencia y seguimiento de campañas
4. Integración con pasarelas de pago
5. Compartir en redes sociales
