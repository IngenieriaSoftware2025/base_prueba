<?php

namespace Model;

class Facturas extends ActiveRecord {

    public static $tabla = 'facturas_venta';
    public static $columnasDB = [
        'factura_numero',
        'cliente_id',
        'factura_fecha',
        'factura_subtotal',
        'factura_iva',
        'factura_descuento',
        'factura_total',
        'factura_estado',
        'factura_observaciones',
        'factura_situacion',
        'fecha_creacion'
    ];

    public static $idTabla = 'factura_id';
    public $factura_id;
    public $factura_numero;
    public $cliente_id;
    public $factura_fecha;
    public $factura_subtotal;
    public $factura_iva;
    public $factura_descuento;
    public $factura_total;
    public $factura_estado;
    public $factura_observaciones;
    public $factura_situacion;
    public $fecha_creacion;

    public function __construct($args = []){
        $this->factura_id = $args['factura_id'] ?? null;
        $this->factura_numero = $args['factura_numero'] ?? '';
        $this->cliente_id = $args['cliente_id'] ?? null;
        $this->factura_fecha = $args['factura_fecha'] ?? '';
        $this->factura_subtotal = $args['factura_subtotal'] ?? 0.00;
        $this->factura_iva = $args['factura_iva'] ?? 0.00;
        $this->factura_descuento = $args['factura_descuento'] ?? 0.00;
        $this->factura_total = $args['factura_total'] ?? 0.00;
        $this->factura_estado = $args['factura_estado'] ?? 'PROCESADA';
        $this->factura_observaciones = $args['factura_observaciones'] ?? '';
        $this->factura_situacion = $args['factura_situacion'] ?? 1;
        $this->fecha_creacion = $args['fecha_creacion'] ?? '';
    }

    public static function GenerarNumeroFactura(){
        
        $fecha = date('Ymd');
        $sql = "SELECT COUNT(*) as total FROM facturas_venta WHERE factura_numero LIKE 'FACT-$fecha%'";
        $resultado = self::fetchFirst($sql);
        
        $consecutivo = str_pad(($resultado['total'] + 1), 4, '0', STR_PAD_LEFT);
        
        return "FACT-$fecha-$consecutivo";
    }

    public static function BuscarPorNumero($numero){
        
        $sql = "SELECT * FROM facturas_venta WHERE factura_numero = '$numero'";
        
        return self::SQL($sql);
    }

}