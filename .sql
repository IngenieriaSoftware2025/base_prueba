CREATE TABLE usuarios (
    usuario_id SERIAL PRIMARY KEY,
    usuario_nombres VARCHAR(255),
    usuario_apellidos VARCHAR(255),
    usuario_nit INT,
    usuario_telefono INT,
    usuario_correo VARCHAR(100),
    usuario_estado CHAR(1),
    usuario_situacion SMALLINT DEFAULT 1
)