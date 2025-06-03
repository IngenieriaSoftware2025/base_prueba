<?php

namespace Model;

class Clientes extends ActiveRecord {

    public static $tabla = 'clientes';
    public static $columnasDB = [
        'cliente_nombres',
        'cliente_apellidos',
        'cliente_email',
        'cliente_telefono',
        'cliente_direccion',
        'cliente_nit',
        'cliente_estado',
        'cliente_situacion',
        'fecha_registro'
    ];

    public static $idTabla = 'cliente_id';
    public $cliente_id;
    public $cliente_nombres;
    public $cliente_apellidos;
    public $cliente_email;
    public $cliente_telefono;
    public $cliente_direccion;
    public $cliente_nit;
    public $cliente_estado;
    public $cliente_situacion;
    public $fecha_registro;

    public function __construct($args = []){
        $this->cliente_id = $args['cliente_id'] ?? null;
        $this->cliente_nombres = $args['cliente_nombres'] ?? '';
        $this->cliente_apellidos = $args['cliente_apellidos'] ?? '';
        $this->cliente_email = $args['cliente_email'] ?? '';
        $this->cliente_telefono = $args['cliente_telefono'] ?? 0;
        $this->cliente_direccion = $args['cliente_direccion'] ?? '';
        $this->cliente_nit = $args['cliente_nit'] ?? '';
        $this->cliente_estado = $args['cliente_estado'] ?? '1';
        $this->cliente_situacion = $args['cliente_situacion'] ?? 1;
        $this->fecha_registro = $args['fecha_registro'] ?? '';
    }

    public static function EliminarCliente($id){

        $sql = "DELETE FROM clientes WHERE cliente_id = $id";

        return self::SQL($sql);
    }

}