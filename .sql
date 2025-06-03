<<<<<<< HEAD
CREATE TABLE clientes (
    cliente_id SERIAL PRIMARY KEY,
    cliente_nombres VARCHAR(255) NOT NULL,
    cliente_apellidos VARCHAR(255) NOT NULL,
    cliente_email VARCHAR(150),
    cliente_telefono INT,
    cliente_direccion VARCHAR(200),
    cliente_nit VARCHAR(15),
    cliente_estado CHAR(1) DEFAULT '1',
    cliente_situacion SMALLINT DEFAULT 1,
    fecha_registro DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);

CREATE TABLE productos (
    producto_id SERIAL PRIMARY KEY,
    producto_nombre VARCHAR(255) NOT NULL,
    producto_descripcion VARCHAR(255),
    producto_precio DECIMAL(10,2) NOT NULL,
    producto_cantidad INT NOT NULL DEFAULT 0,
    producto_stock_minimo INT DEFAULT 5,
    producto_estado CHAR(1) DEFAULT '1',
    producto_situacion SMALLINT DEFAULT 1,
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    fecha_actualizacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);


CREATE TABLE facturas_venta (
    factura_id SERIAL PRIMARY KEY,
    factura_numero VARCHAR(50) NOT NULL UNIQUE,
    cliente_id INT NOT NULL,
    factura_fecha DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    factura_subtotal DECIMAL(10,2) NOT NULL,
    factura_iva DECIMAL(10,2) DEFAULT 0.00,
    factura_descuento DECIMAL(10,2) DEFAULT 0.00,
    factura_total DECIMAL(10,2) NOT NULL,
    factura_estado VARCHAR(20) DEFAULT 'PROCESADA',
    factura_observaciones LVARCHAR(500),
    factura_situacion SMALLINT DEFAULT 1,
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id)
);

CREATE TABLE detalle_ventas (
    detalle_id SERIAL PRIMARY KEY,
    factura_id INT NOT NULL,
    producto_id INT NOT NULL,
    detalle_cantidad INT NOT NULL,
    detalle_precio_unitario DECIMAL(10,2) NOT NULL,
    detalle_subtotal DECIMAL(10,2) NOT NULL,
    detalle_descuento DECIMAL(10,2) DEFAULT 0.00,
    detalle_total DECIMAL(10,2) NOT NULL,
    detalle_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (factura_id) REFERENCES facturas_venta(factura_id),
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id)
);

CREATE TABLE carrito_temporal (
    carrito_id SERIAL PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    producto_id INT NOT NULL,
    carrito_cantidad INT NOT NULL,
    carrito_precio DECIMAL(10,2) NOT NULL,
    fecha_agregado DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    FOREIGN KEY (producto_id) REFERENCES productos(producto_id)
);
=======
CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE prioridades (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    valor INT NOT NULL -- Para establecer un orden (1: Alta, 2: Media, 3: Baja)
);

CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    categoria_id INT NOT NULL,
    prioridad_id INT NOT NULL,
    comprado BOOLEAN DEFAULT 'f',
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (prioridad_id) REFERENCES prioridades(id)
);
>>>>>>> 2837b4513d33b51c1442a698f2ca7584cfbfce22
