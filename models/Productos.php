<?php

namespace Model;

class Productos extends ActiveRecord {

    public static $tabla = 'productos';
    public static $columnasDB = [
        'producto_nombre',
        'producto_descripcion',
        'producto_precio',
        'producto_cantidad',
        'producto_stock_minimo',
        'producto_estado',
        'producto_situacion',
        'fecha_creacion',
        'fecha_actualizacion'
    ];

    public static $idTabla = 'producto_id';
    public $producto_id;
    public $producto_nombre;
    public $producto_descripcion;
    public $producto_precio;
    public $producto_cantidad;
    public $producto_stock_minimo;
    public $producto_estado;
    public $producto_situacion;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($args = []){
        $this->producto_id = $args['producto_id'] ?? null;
        $this->producto_nombre = $args['producto_nombre'] ?? '';
        $this->producto_descripcion = $args['producto_descripcion'] ?? '';
        $this->producto_precio = $args['producto_precio'] ?? 0.00;
        $this->producto_cantidad = $args['producto_cantidad'] ?? 0;
        $this->producto_stock_minimo = $args['producto_stock_minimo'] ?? 5;
        $this->producto_estado = $args['producto_estado'] ?? '1';
        $this->producto_situacion = $args['producto_situacion'] ?? 1;
        $this->fecha_creacion = $args['fecha_creacion'] ?? '';
        $this->fecha_actualizacion = $args['fecha_actualizacion'] ?? '';
    }

    public static function EliminarProducto($id){

        $sql = "DELETE FROM productos WHERE producto_id = $id";

        return self::SQL($sql);
    }

    public static function ValidarStock($id, $cantidad){
        
        $sql = "SELECT producto_cantidad FROM productos WHERE producto_id = $id AND producto_situacion = 1";
        $resultado = self::fetchFirst($sql);
        
        if($resultado && $resultado['producto_cantidad'] >= $cantidad){
            return true;
        }
        
        return false;
    }

    public static function DescontarStock($id, $cantidad){
        
        $sql = "UPDATE productos SET producto_cantidad = producto_cantidad - $cantidad WHERE producto_id = $id";
        
        return self::SQL($sql);
    }

}