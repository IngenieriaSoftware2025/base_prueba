<?php

namespace Controllers;

use DateTime;
use Exception;
use Model\ActiveRecord;
use Model\Clientes;
use Model\Productos;
use Model\Facturas;
use Model\FacturaSituacion; 
use MVC\Router;

class FacturasController extends ActiveRecord
{

    public function renderizarPagina(Router $router)
    {
        $router->render('ventas/index', []);
    }

    public function renderizarPaginaModificar(Router $router)
    {
        $router->render('ventas/modificar', []);
    }

    public function renderizarPaginaFacturas(Router $router)
    {
        $router->render('facturas/index', []);
    }

    public static function buscarClientesAPI()
    {
        getHeadersApi();
        try {
            $sql = "SELECT cliente_id, cliente_nombres, cliente_apellidos, cliente_email, cliente_telefono, cliente_direccion, cliente_nit 
                    FROM clientes 
                    WHERE cliente_situacion = 1 
                    ORDER BY cliente_nombres, cliente_apellidos";
            $clientes = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes encontrados correctamente',
                'data' => $clientes
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar los clientes',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarProductosDisponiblesAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT producto_id, producto_nombre, producto_descripcion, producto_precio, producto_cantidad 
                    FROM productos 
                    WHERE producto_situacion = 1 AND producto_estado = 'D' AND producto_cantidad > 0 
                    ORDER BY producto_nombre";
            $productos = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos disponibles obtenidos correctamente',
                'data' => $productos
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos disponibles',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function guardarFacturaAPI()
    {
        getHeadersApi();

        date_default_timezone_set('America/Guatemala');
        $fecha = new DateTime();

        if (empty($_POST['cliente_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente para procesar la factura'
            ]);
            return;
        }

        if (empty($_POST['productos_seleccionados'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar al menos un producto para la factura'
            ]);
            return;
        }

        try {
            $numeroFactura = Facturas::GenerarNumeroFactura(); 
            $subtotal = 0;
            
            $productos = json_decode($_POST['productos_seleccionados'], true);
            
            if (!$productos || !is_array($productos)) {
                throw new Exception('Formato de productos inválido');
            }

            foreach ($productos as $producto) {
                $cantidad = intval($producto['cantidad']);
                $precio = floatval($producto['precio']);
                
                if (!Productos::ValidarStock(intval($producto['id']), $cantidad)) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => "Stock insuficiente para el producto ID {$producto['id']}"
                    ]);
                    return;
                }
                
                $subtotal += ($cantidad * $precio);
            }

            $iva = $subtotal * 0.12;
            $descuento = floatval($_POST['descuento_aplicado'] ?? 0);
            $total = $subtotal + $iva - $descuento;

            $sql = "INSERT INTO facturas_venta (factura_numero, cliente_id, factura_fecha, factura_subtotal, factura_iva, factura_descuento, factura_total, factura_estado, factura_observaciones, fecha_creacion, factura_situacion) 
                    VALUES ('{$numeroFactura}', {$_POST['cliente_id']}, '{$fecha->format('Y-m-d H:i:s')}', {$subtotal}, {$iva}, {$descuento}, {$total}, 'PROCESADA', '" . htmlspecialchars($_POST['observaciones_factura'] ?? '') . "', '{$fecha->format('Y-m-d H:i:s')}', 1)";
            
            $resultado = self::SQL($sql);
            $idFactura = self::$db->lastInsertId();
            $resultadoFactura = ['resultado' => $resultado, 'id' => $idFactura];

            if ($resultadoFactura['resultado']) {
                $idFactura = $resultadoFactura['id'];
                
                foreach ($productos as $producto) {
                    $cantidad = intval($producto['cantidad']);
                    $precio = floatval($producto['precio']);
                    $subtotalLinea = $cantidad * $precio;
                    
                    $detalle = new FacturaSituacion([
                        'factura_id' => $idFactura,
                        'producto_id' => intval($producto['id']),
                        'detalle_cantidad' => $cantidad,
                        'detalle_precio_unitario' => $precio,
                        'detalle_subtotal' => $subtotalLinea,
                        'detalle_descuento' => 0.00,
                        'detalle_total' => $subtotalLinea,
                        'detalle_situacion' => 1
                    ]);

                    $detalle->crear();
                    Productos::DescontarStock(intval($producto['id']), $cantidad);
                }

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Exito la factura ha sido procesada correctamente',
                    'numero_factura' => $numeroFactura,
                    'factura_id' => $idFactura
                ]);
            } else {
                throw new Exception('Error al crear la factura en la base de datos');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al procesar la factura',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $estado = isset($_GET['estado']) ? $_GET['estado'] : null;

            $condiciones = ["f.factura_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "f.factura_fecha >= '{$fecha_inicio} 00:00:00'";
            }

            if ($fecha_fin) {
                $condiciones[] = "f.factura_fecha <= '{$fecha_fin} 23:59:59'";
            }

            if ($estado && $estado != 'TODOS') {
                $condiciones[] = "f.factura_estado = '$estado'";
            }

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT f.factura_id, f.factura_numero, f.factura_fecha, f.factura_total, f.factura_estado, 
                           c.cliente_nombres, c.cliente_apellidos, c.cliente_nit
                    FROM facturas_venta f 
                    INNER JOIN clientes c ON f.cliente_id = c.cliente_id 
                    WHERE $where 
                    ORDER BY f.factura_fecha DESC";

            $facturas = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Facturas obtenidas correctamente',
                'data' => $facturas
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las facturas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function buscarFacturaPorIdAPI()
    {
        getHeadersApi();

        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El ID de factura es requerido'
            ]);
            return;
        }

        try {
            $idFactura = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $sqlFactura = "SELECT f.factura_id, f.factura_numero, f.cliente_id, f.factura_fecha, 
                                  f.factura_subtotal, f.factura_iva, f.factura_descuento, 
                                  f.factura_total, f.factura_estado, f.factura_observaciones,
                                  c.cliente_nombres, c.cliente_apellidos, c.cliente_email, 
                                  c.cliente_telefono, c.cliente_nit, c.cliente_direccion
                           FROM facturas_venta f 
                           INNER JOIN clientes c ON f.cliente_id = c.cliente_id 
                           WHERE f.factura_id = $idFactura AND f.factura_situacion = 1";

            $factura = self::fetchFirst($sqlFactura);

            if (!$factura) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Factura no encontrada en el sistema'
                ]);
                return;
            }

            $detalles = FacturaSituacion::ObtenerDetallesPorFactura($idFactura);

            $facturaCompleta = [
                'informacion_factura' => $factura,
                'productos_factura' => $detalles,
                'datos_cliente' => [
                    'cliente_id' => $factura['cliente_id'],
                    'cliente_nombres' => $factura['cliente_nombres'],
                    'cliente_apellidos' => $factura['cliente_apellidos'],
                    'cliente_email' => $factura['cliente_email'],
                    'cliente_telefono' => $factura['cliente_telefono'],
                    'cliente_nit' => $factura['cliente_nit'],
                    'cliente_direccion' => $factura['cliente_direccion']
                ]
            ];

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Factura encontrada correctamente',
                'data' => $facturaCompleta
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar la factura',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        date_default_timezone_set('America/Guatemala');

        if (empty($_POST['factura_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El ID de factura es requerido para la modificacion'
            ]);
            return;
        }

        if (empty($_POST['productos_actualizados'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar al menos un producto para actualizar la factura'
            ]);
            return;
        }

        try {
            $idFactura = intval($_POST['factura_id']);
            
            $sqlVerificar = "SELECT factura_id, factura_numero FROM facturas_venta 
                            WHERE factura_id = $idFactura AND factura_situacion = 1";
            $facturaExistente = self::fetchFirst($sqlVerificar);

            if (!$facturaExistente) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Factura no encontrada para modificacion'
                ]);
                return;
            }

            $detallesOriginales = FacturaSituacion::ObtenerDetallesPorFactura($idFactura) ?: [];

            foreach ($detallesOriginales as $detalle) {
                Productos::DescontarStock($detalle['producto_id'], -$detalle['detalle_cantidad']);
            }

            if (!empty($detallesOriginales)) {
                FacturaSituacion::EliminarDetallesPorFactura($idFactura);
            }

            $subtotal = 0;
            $productos = json_decode($_POST['productos_actualizados'], true);

            foreach ($productos as $producto) {
                $cantidad = intval($producto['cantidad']);
                $precio = floatval($producto['precio']);
                
                if (!Productos::ValidarStock($producto['id'], $cantidad)) {
                    throw new Exception("Stock insuficiente para producto ID {$producto['id']}");
                }

                $subtotal += ($cantidad * $precio);
            }

            $iva = $subtotal * 0.12;
            $descuento = floatval($_POST['descuento_actualizado'] ?? 0);
            $total = $subtotal + $iva - $descuento;

            $facturaData = Facturas::find($idFactura);
            $facturaData->sincronizar([
                'factura_subtotal' => $subtotal,
                'factura_iva' => $iva,
                'factura_descuento' => $descuento,
                'factura_total' => $total,
                'factura_observaciones' => htmlspecialchars($_POST['observaciones_actualizadas'] ?? '')
            ]);
            $facturaData->actualizar();

            foreach ($productos as $producto) {
                $cantidad = intval($producto['cantidad']);
                $precio = floatval($producto['precio']);
                $subtotalLinea = $cantidad * $precio;
                
                $detalle = new FacturaSituacion([
                    'factura_id' => $idFactura,
                    'producto_id' => intval($producto['id']),
                    'detalle_cantidad' => $cantidad,
                    'detalle_precio_unitario' => $precio,
                    'detalle_subtotal' => $subtotalLinea,
                    'detalle_descuento' => 0.00,
                    'detalle_total' => $subtotalLinea,
                    'detalle_situacion' => 1
                ]);

                $detalle->crear();
                Productos::DescontarStock($producto['id'], $cantidad);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La factura ha sido modificada exitosamente',
                'numero_factura' => $facturaExistente['factura_numero']
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la factura',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}