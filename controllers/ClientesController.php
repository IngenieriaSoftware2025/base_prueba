<?php

namespace Controllers;

use DateTime;
use Exception;
use Model\ActiveRecord;
use Model\Clientes;
use MVC\Router;

class ClientesController extends ActiveRecord
{

   public function renderizarPagina(Router $router)
   {
       $router->render('clientes/clientes', []);
   }

   public static function guardarAPI()
   {

       getHeadersApi();

       date_default_timezone_set('America/Guatemala');

       $_POST['cliente_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_nombres']))));

       $cantidad_nombres = strlen($_POST['cliente_nombres']);

       if ($cantidad_nombres < 2) {

           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos que debe de contener el nombre debe de ser mayor a dos'
           ]);
           return;
       }

       $_POST['cliente_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_apellidos']))));

       $cantidad_apellidos = strlen($_POST['cliente_apellidos']);

       if ($cantidad_apellidos < 2) {

           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos que debe de contener el apellido debe de ser mayor a dos'
           ]);
           return;
       }

       $_POST['cliente_telefono'] = filter_var($_POST['cliente_telefono'], FILTER_VALIDATE_INT);

       if (strlen($_POST['cliente_telefono']) != 8) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos de telefono debe de ser igual a 8'
           ]);
           return;
       }

       $_POST['cliente_email'] = filter_var($_POST['cliente_email'], FILTER_SANITIZE_EMAIL);

       if (!empty($_POST['cliente_email']) && !filter_var($_POST['cliente_email'], FILTER_VALIDATE_EMAIL)) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'El correo electronico ingresado es invalido'
           ]);
           return;
       }

       $_POST['cliente_nit'] = htmlspecialchars($_POST['cliente_nit']);
       $_POST['cliente_direccion'] = htmlspecialchars($_POST['cliente_direccion']);
       $_POST['cliente_estado'] = htmlspecialchars($_POST['cliente_estado']);

       $_POST['fecha_registro'] = date('Y-m-d H:i:s');

       $estado = $_POST['cliente_estado'];

       if ($estado == "A" || $estado == "I") {

           try {

               $data = new Clientes([
                   'cliente_nombres' => $_POST['cliente_nombres'],
                   'cliente_apellidos' => $_POST['cliente_apellidos'],
                   'cliente_email' => $_POST['cliente_email'],
                   'cliente_telefono' => $_POST['cliente_telefono'],
                   'cliente_direccion' => $_POST['cliente_direccion'],
                   'cliente_nit' => $_POST['cliente_nit'],
                   'cliente_estado' => $_POST['cliente_estado'],
                   'fecha_registro' => $_POST['fecha_registro'],
                   'cliente_situacion' => 1
               ]);

               $crear = $data->crear();

               http_response_code(200);
               echo json_encode([
                   'codigo' => 1,
                   'mensaje' => 'Exito el cliente ha sido registrado correctamente'
               ]);
           } catch (Exception $e) {
               http_response_code(400);
               echo json_encode([
                   'codigo' => 0,
                   'mensaje' => 'Error al guardar el cliente',
                   'detalle' => $e->getMessage(),
               ]);
           }
       } else {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Los estados solo pueden ser "A" para Activo o "I" para Inactivo'
           ]);
           return;
       }
   }

   public static function buscarAPI()
   {
       try {

           $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
           $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

           $condiciones = ["cliente_situacion = 1"];

           if ($fecha_inicio) {
               $condiciones[] = "fecha_registro >= '{$fecha_inicio} 00:00:00'";
           }

           if ($fecha_fin) {
               $condiciones[] = "fecha_registro <= '{$fecha_fin} 23:59:59'";
           }

           $where = implode(" AND ", $condiciones);

           $sql = "SELECT * FROM clientes WHERE $where ORDER BY cliente_nombres, cliente_apellidos";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Clientes obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los clientes',
               'detalle' => $e->getMessage(),
           ]);
       }
   }

   public static function modificarAPI()
   {

       getHeadersApi();

       date_default_timezone_set('America/Guatemala');

       $id = $_POST['cliente_id'];

       $_POST['cliente_nombres'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_nombres']))));

       $cantidad_nombres = strlen($_POST['cliente_nombres']);

       if ($cantidad_nombres < 2) {

           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos que debe de contener el nombre debe de ser mayor a dos'
           ]);
           return;
       }

       $_POST['cliente_apellidos'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_apellidos']))));

       $cantidad_apellidos = strlen($_POST['cliente_apellidos']);

       if ($cantidad_apellidos < 2) {

           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos que debe de contener el apellido debe de ser mayor a dos'
           ]);
           return;
       }

       $_POST['cliente_telefono'] = filter_var($_POST['cliente_telefono'], FILTER_VALIDATE_INT);

       if (strlen($_POST['cliente_telefono']) != 8) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'La cantidad de digitos de telefono debe de ser igual a 8'
           ]);
           return;
       }

       $_POST['cliente_email'] = filter_var($_POST['cliente_email'], FILTER_SANITIZE_EMAIL);
       $_POST['cliente_nit'] = htmlspecialchars($_POST['cliente_nit']);
       $_POST['cliente_direccion'] = htmlspecialchars($_POST['cliente_direccion']);
       $_POST['cliente_estado'] = htmlspecialchars($_POST['cliente_estado']);

       $estado = $_POST['cliente_estado'];

       if ($estado == "A" || $estado == "I") {

           try {

               $data = Clientes::find($id);
               $data->sincronizar([
                   'cliente_nombres' => $_POST['cliente_nombres'],
                   'cliente_apellidos' => $_POST['cliente_apellidos'],
                   'cliente_email' => $_POST['cliente_email'],
                   'cliente_telefono' => $_POST['cliente_telefono'],
                   'cliente_direccion' => $_POST['cliente_direccion'],
                   'cliente_nit' => $_POST['cliente_nit'],
                   'cliente_estado' => $_POST['cliente_estado'],
                   'cliente_situacion' => 1
               ]);
               $data->actualizar();

               http_response_code(200);
               echo json_encode([
                   'codigo' => 1,
                   'mensaje' => 'La informacion del cliente ha sido modificada exitosamente'
               ]);
           } catch (Exception $e) {
               http_response_code(400);
               echo json_encode([
                   'codigo' => 0,
                   'mensaje' => 'Error al modificar el cliente',
                   'detalle' => $e->getMessage(),
               ]);
           }
       } else {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Los estados solo pueden ser "A" para Activo o "I" para Inactivo'
           ]);
           return;
       }
   }

   public static function EliminarAPI()
   {

       try {

           $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

           $sql = "SELECT COUNT(*) as facturas FROM facturas_venta WHERE cliente_id = $id AND factura_situacion = 1";
           $resultado = self::fetchFirst($sql);

           if ($resultado['facturas'] > 0) {
               http_response_code(400);
               echo json_encode([
                   'codigo' => 0,
                   'mensaje' => 'No se puede eliminar el cliente porque tiene facturas asociadas'
               ]);
               return;
           }

           $ejecutar = Clientes::EliminarCliente($id);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'El cliente ha sido eliminado correctamente'
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al eliminar el cliente',
               'detalle' => $e->getMessage(),
           ]);
       }
   }
}