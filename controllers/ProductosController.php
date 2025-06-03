<?php

namespace Controllers;

use DateTime;
use Exception;
use Model\ActiveRecord;
use Model\Productos;
use MVC\Router;

class ProductosController extends ActiveRecord
{

    public function renderizarPagina(Router $router)
    {
        $router->render('productos/index', []);
    }

    public static function guardarAPI()
    {

        getHeadersApi();

        date_default_timezone_set('America/Guatemala');
        $fecha = new DateTime();

        $_POST['producto_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['producto_nombre']))));

        $cantidad_nombre = strlen($_POST['producto_nombre']);

        if ($cantidad_nombre < 2) {

            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el nombre debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['producto_precio'] = filter_var($_POST['producto_precio'], FILTER_VALIDATE_FLOAT);

        if ($_POST['producto_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio del producto debe ser mayor a cero'
            ]);
            return;
        }

        $_POST['producto_cantidad'] = filter_var($_POST['producto_cantidad'], FILTER_VALIDATE_INT);

        if ($_POST['producto_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto no puede ser negativa'
            ]);
            return;
        }

        $_POST['producto_stock_minimo'] = filter_var($_POST['producto_stock_minimo'], FILTER_VALIDATE_INT);

        if ($_POST['producto_stock_minimo'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El stock minimo no puede ser negativo'
            ]);
            return;
        }

        $_POST['producto_descripcion'] = isset($_POST['producto_descripcion']) ? htmlspecialchars($_POST['producto_descripcion']) : '';
        $_POST['producto_estado'] = htmlspecialchars($_POST['producto_estado']);

        $_POST['fecha_creacion'] = $fecha->format('Y-m-d H:i:s');
        $_POST['fecha_actualizacion'] = $fecha->format('Y-m-d H:i:s');

        $estado = $_POST['producto_estado'];

        if ($estado == "D" || $estado == "N") {

            try {

                $data = new Productos([
                    'producto_nombre' => $_POST['producto_nombre'],
                    'producto_descripcion' => $_POST['producto_descripcion'],
                    'producto_precio' => $_POST['producto_precio'],
                    'producto_cantidad' => $_POST['producto_cantidad'],
                    'producto_stock_minimo' => $_POST['producto_stock_minimo'],
                    'producto_estado' => $_POST['producto_estado'],
                    'fecha_creacion' => $_POST['fecha_creacion'],
                    'fecha_actualizacion' => $_POST['fecha_actualizacion'],
                    'producto_situacion' => 1
                ]);

                $crear = $data->crear();

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Exito el producto ha sido registrado correctamente'
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al guardar el producto',
                    'detalle' => $e->getMessage(),
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los estados solo pueden ser "D" para Disponible o "N" para No disponible'
            ]);
            return;
        }
    }

    public static function buscarAPI()
    {
        try {

            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $estado = isset($_GET['estado']) ? $_GET['estado'] : null;

            $condiciones = ["producto_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "fecha_creacion >= '{$fecha_inicio} 00:00:00'";
            }

            if ($fecha_fin) {
                $condiciones[] = "fecha_creacion <= '{$fecha_fin} 23:59:59'";
            }

            if ($estado && $estado != 'TODOS') {
                $condiciones[] = "producto_estado = '$estado'";
            }

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT * FROM productos WHERE $where ORDER BY producto_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {

        getHeadersApi();

        date_default_timezone_set('America/Guatemala');
        $fecha = new DateTime();

        $id = $_POST['producto_id'];

        $_POST['producto_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['producto_nombre']))));

        $cantidad_nombre = strlen($_POST['producto_nombre']);

        if ($cantidad_nombre < 2) {

            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de digitos que debe de contener el nombre debe de ser mayor a dos'
            ]);
            return;
        }

        $_POST['producto_precio'] = filter_var($_POST['producto_precio'], FILTER_VALIDATE_FLOAT);

        if ($_POST['producto_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio del producto debe ser mayor a cero'
            ]);
            return;
        }

        $_POST['producto_cantidad'] = filter_var($_POST['producto_cantidad'], FILTER_VALIDATE_INT);

        if ($_POST['producto_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad del producto no puede ser negativa'
            ]);
            return;
        }

        $_POST['producto_stock_minimo'] = filter_var($_POST['producto_stock_minimo'], FILTER_VALIDATE_INT);

        if ($_POST['producto_stock_minimo'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El stock minimo no puede ser negativo'
            ]);
            return;
        }

        $_POST['producto_descripcion'] = isset($_POST['producto_descripcion']) ? htmlspecialchars($_POST['producto_descripcion']) : '';
        $_POST['producto_estado'] = htmlspecialchars($_POST['producto_estado']);

        $_POST['fecha_actualizacion'] = $fecha->format('Y-m-d H:i:s');

        $estado = $_POST['producto_estado'];

        if ($estado == "D" || $estado == "N") {

            try {

                $data = Productos::find($id);
                $data->sincronizar([
                    'producto_nombre' => $_POST['producto_nombre'],
                    'producto_descripcion' => $_POST['producto_descripcion'],
                    'producto_precio' => $_POST['producto_precio'],
                    'producto_cantidad' => $_POST['producto_cantidad'],
                    'producto_stock_minimo' => $_POST['producto_stock_minimo'],
                    'producto_estado' => $_POST['producto_estado'],
                    'fecha_actualizacion' => $_POST['fecha_actualizacion'],
                    'producto_situacion' => 1
                ]);
                $data->actualizar();

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La informacion del producto ha sido modificada exitosamente'
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar el producto',
                    'detalle' => $e->getMessage(),
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los estados solo pueden ser "D" para Disponible o "N" para No disponible'
            ]);
            return;
        }
    }

    public static function EliminarAPI()
    {

        try {

            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT producto_cantidad FROM productos WHERE producto_id = $id AND producto_situacion = 1";
            $resultado = self::fetchFirst($sql);

            if (!$resultado) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Producto no encontrado en el sistema'
                ]);
                return;
            }

            if ($resultado['producto_cantidad'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar el producto porque tiene existencia en stock'
                ]);
                return;
            }

            $ejecutar = Productos::EliminarProducto($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el producto',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerProductosDisponiblesAPI()
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
                'mensaje' => 'Error al obtener productos disponibles',
                'detalle' => $e->getMessage()
            ]);
        }
    }

}