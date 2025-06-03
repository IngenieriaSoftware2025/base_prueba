<?php

namespace Model;

class FacturaSituacion extends ActiveRecord {

    public static $tabla = 'detalle_ventas';
    public static $columnasDB = [
        'factura_id',
        'producto_id',
        'detalle_cantidad',
        'detalle_precio_unitario',
        'detalle_subtotal',
        'detalle_descuento',
        'detalle_total',
        'detalle_situacion'
    ];

    public static $idTabla = 'detalle_id';
    public $detalle_id;
    public $factura_id;
    public $producto_id;
    public $detalle_cantidad;
    public $detalle_precio_unitario;
    public $detalle_subtotal;
    public $detalle_descuento;
    public $detalle_total;
    public $detalle_situacion;

    public function __construct($args = []){
        $this->detalle_id = $args['detalle_id'] ?? null;
        $this->factura_id = $args['factura_id'] ?? null;
        $this->producto_id = $args['producto_id'] ?? null;
        $this->detalle_cantidad = $args['detalle_cantidad'] ?? 0;
        $this->detalle_precio_unitario = $args['detalle_precio_unitario'] ?? 0.00;
        $this->detalle_subtotal = $args['detalle_subtotal'] ?? 0.00;
        $this->detalle_descuento = $args['detalle_descuento'] ?? 0.00;
        $this->detalle_total = $args['detalle_total'] ?? 0.00;
        $this->detalle_situacion = $args['detalle_situacion'] ?? 1;
    }

    public static function ObtenerDetallesPorFactura($factura_id){
        try {
            $factura_id = intval($factura_id);
            if ($factura_id <= 0) {
                return [];
            }

            $sql = "SELECT dv.*, p.producto_nombre 
                    FROM detalle_ventas dv 
                    INNER JOIN productos p ON dv.producto_id = p.producto_id 
                    WHERE dv.factura_id = $factura_id 
                    AND dv.detalle_situacion = 1
                    ORDER BY dv.detalle_id";

            $resultado = self::fetchArray($sql);
            return $resultado ?: [];
        } catch (\Exception $e) {
            error_log("Error obteniendo detalles de factura $factura_id: " . $e->getMessage());
            return [];
        }
    }

    public static function EliminarDetallesPorFactura($factura_id){
        try {
            $factura_id = intval($factura_id);
            if ($factura_id <= 0) {
                return false;
            }

            $sql = "DELETE FROM detalle_ventas WHERE factura_id = $factura_id";
            
            $resultado = self::SQL($sql);
            
            return $resultado;
            
        } catch (\Exception $e) {
            error_log("Error eliminando detalles de factura $factura_id: " . $e->getMessage());
            return false;
        }
    }
    
    public static function InactivarDetallesPorFactura($factura_id){
        try {
            $factura_id = intval($factura_id);
            if ($factura_id <= 0) {
                return false;
            }

            $sql = "UPDATE detalle_ventas 
                    SET detalle_situacion = 0 
                    WHERE factura_id = $factura_id 
                    AND detalle_situacion = 1";
            
            $resultado = self::SQL($sql);
            return $resultado;
            
        } catch (\Exception $e) {
            error_log("Error inactivando detalles de factura $factura_id: " . $e->getMessage());
            return false;
        }
    }

    public static function ObtenerTotalDetalles($factura_id){
        try {
            $factura_id = intval($factura_id);
            if ($factura_id <= 0) {
                return 0;
            }

            $sql = "SELECT COUNT(*) as total 
                    FROM detalle_ventas 
                    WHERE factura_id = $factura_id 
                    AND detalle_situacion = 1";

            $resultado = self::fetchFirst($sql);
            return $resultado ? intval($resultado['total']) : 0;
            
        } catch (\Exception $e) {
            error_log("Error contando detalles de factura $factura_id: " . $e->getMessage());
            return 0;
        }
    }

    public static function FacturaTieneDetalles($factura_id){
        return self::ObtenerTotalDetalles($factura_id) > 0;
    }

    public static function CalcularSubtotalFactura($factura_id){
        try {
            $factura_id = intval($factura_id);
            if ($factura_id <= 0) {
                return 0.00;
            }

            $sql = "SELECT SUM(detalle_total) as subtotal 
                    FROM detalle_ventas 
                    WHERE factura_id = $factura_id 
                    AND detalle_situacion = 1";

            $resultado = self::fetchFirst($sql);
            return $resultado ? floatval($resultado['subtotal']) : 0.00;
            
        } catch (\Exception $e) {
            error_log("Error calculando subtotal de factura $factura_id: " . $e->getMessage());
            return 0.00;
        }
    }

}