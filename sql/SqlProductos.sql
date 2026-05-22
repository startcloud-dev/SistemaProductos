-- 1. ELIMINACIÓN DE TABLAS (En orden inverso por dependencias)
DROP TABLE IF EXISTS productosmateriales CASCADE;
DROP TABLE IF EXISTS productos CASCADE;
DROP TABLE IF EXISTS material CASCADE;
DROP TABLE IF EXISTS moneda CASCADE;
DROP TABLE IF EXISTS sucursal CASCADE;
DROP TABLE IF EXISTS bodega CASCADE;


-- 2. CREACIÓN DE TABLAS
CREATE TABLE bodega (
    idBodega SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE sucursal (
    idSucursal SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE moneda (
    idMoneda SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE material (
    idMaterial SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE productos (
    codigo VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    idBodega INT,
    idSucursal INT,
    idMoneda INT,
    precio NUMERIC(12,2) NOT NULL,
    descripcion TEXT,
    fechaCreacion TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_producto_bodega FOREIGN KEY (idBodega) REFERENCES bodega(idBodega) ON DELETE SET NULL,
    CONSTRAINT fk_producto_sucursal FOREIGN KEY (idSucursal) REFERENCES sucursal(idSucursal) ON DELETE SET NULL,
    CONSTRAINT fk_producto_moneda FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda) ON DELETE SET NULL
);

CREATE TABLE productosmateriales (
    idProductoMaterial SERIAL PRIMARY KEY,
    codigoProducto VARCHAR(20) NOT NULL,
    idMaterial INT NOT NULL,

    CONSTRAINT fk_producto_codigo FOREIGN KEY (codigoProducto) REFERENCES productos(codigo) ON DELETE CASCADE,
    CONSTRAINT fk_material_IdMaterial FOREIGN KEY (idMaterial) REFERENCES material(idMaterial) ON DELETE CASCADE,

    CONSTRAINT uq_producto_material UNIQUE (codigoProducto, idMaterial)
);


-- 3. INSERCIÓN DE DATOS INICIALES
INSERT INTO bodega (nombre) VALUES 
('Bodega 1'),
('Bodega Central'),
('Bodega Norte')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO sucursal (nombre) VALUES 
('Sucursal 1'),
('Sucursal 2'),
('Sucursal Oriente')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO moneda (nombre) VALUES 
('DÓLAR'),
('PESO'),
('EURO')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO material (nombre) VALUES 
('plastico'),
('metal'),
('madera'),
('vidrio'),
('textil')
ON CONFLICT (nombre) DO NOTHING;
