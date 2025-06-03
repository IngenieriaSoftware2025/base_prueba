<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\ProductosController;
use Controllers\ClientesController;
use Controllers\FacturasController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

$router->get('/productos', [ProductosController::class, 'renderizarPagina']);
$router->post('/productos/guardarAPI', [ProductosController::class, 'guardarAPI']);
$router->get('/productos/buscarAPI', [ProductosController::class, 'buscarAPI']);
$router->post('/productos/modificarAPI', [ProductosController::class, 'modificarAPI']);
$router->get('/productos/eliminar', [ProductosController::class, 'EliminarAPI']);
$router->get('/productos/disponiblesAPI', [ProductosController::class, 'obtenerProductosDisponiblesAPI']);

$router->get('/clientes', [ClientesController::class, 'renderizarPagina']);
$router->post('/clientes/guardarAPI', [ClientesController::class, 'guardarAPI']);
$router->get('/clientes/buscarAPI', [ClientesController::class, 'buscarAPI']);
$router->post('/clientes/modificarAPI', [ClientesController::class, 'modificarAPI']);
$router->get('/clientes/eliminar', [ClientesController::class, 'EliminarAPI']);

$router->get('/ventas', [FacturasController::class, 'renderizarPagina']);
$router->get('/facturas', [FacturasController::class, 'renderizarPaginaFacturas']);
$router->get('/facturas/modificar', [FacturasController::class, 'renderizarPaginaModificar']);
$router->get('/ventas/buscarFacturaPorIdAPI', [FacturasController::class, 'buscarFacturaPorIdAPI']);
$router->get('/ventas/buscarClientesAPI', [FacturasController::class, 'buscarClientesAPI']);
$router->get('/ventas/buscarProductosDisponiblesAPI', [FacturasController::class, 'buscarProductosDisponiblesAPI']);
$router->post('/ventas/guardarFacturaAPI', [FacturasController::class, 'guardarFacturaAPI']);
$router->get('/facturas/buscarAPI', [FacturasController::class, 'buscarAPI']);
$router->post('/facturas/modificarAPI', [FacturasController::class, 'modificarAPI']);

$router->comprobarRutas();