Create table articulos(
    id mediumint unsigned not null auto_increment primary key,
    id_seccion mediumint not null references seccion(id),
    id_familia mediumint not null references familia(id),
    codigo mediumint not null,
    id_fabricante mediumint not null references fabricante(id),
    referencia_fabricante char(50),
    descripcion_fabricante char(50),
    ruta_foto text,
    observaciones char(40),
    /* aqui empieza la parte personalizada */
    propiedad1 text,
    propiedad2 text,
    propiedad3 text,
    propiedad4 text,
    propiedad5 text,
    propiedad6 text,
    propiedad7 text,
    propiedad8 text,
    propiedad9 text,
    propiedad10 text,
    propiedad11 text
);

Create table articuloFoto(
    id mediumint unsigned not null auto_increment primary key,
    id_articulo mediumint not null references articulos(id),
    ruta_foto text
);

Create table articuloProveedor(
    id mediumint unsigned not null auto_increment primary key,
    id_articulo mediumint not null references articulos(id),
    id_proveedor mediumint not null references proveedor(id),
    ref_proveedor char(50),
    UNIQUE KEY `artProve` (`id_articulo`, `id_proveedor`, `ref_proveedor`)
);

Create table fabricantes(
    id mediumint unsigned not null auto_increment primary key,
    referencia char (50),
    descripcion_fabricante char(50),
    nombre char(50),
    telefonos char(50)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `proveedores` (
  `id` int(11) not null auto_increment primary key,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `contacto` varchar(50) NOT NULL,
  `telefonos` varchar(50) NOT NULL,
  `observaciones` text NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

Create table seccion(
    id mediumint unsigned not null auto_increment primary key,
    codigo char(5) not null,
    descripcion char(20)
);

Create table familia(
    id mediumint unsigned not null auto_increment primary key,
    codigo char(5) not null,
    descripcion char(20),
    id_seccion mediumint not null references seccion(id)
);
Create table grupo(
    id mediumint unsigned not null auto_increment primary key,
    codigo char(5) not null,
    descripcion char(20),
    id_familia mediumint not null references familia(id)
);

Create table historicoCompras(
    id mediumint unsigned not null auto_increment primary key,
    fecha_compra date,
    hora_in TIME(4) NULL,
    coste_empresa decimal(9, 2),
    cantidad int (10),
    precio_unidad decimal(9, 2),
    id_articulo mediumint references articulos(id),
    id_proveedor mediumint references proveedor(id)
);

Create table articuloUbicacion(
    id mediumint unsigned not null auto_increment primary key,
    ubicacion char(5) not null references seccion(codigo),
    cantidad int(10),
    id_articulo mediumint unsigned not null references articulos(id)
);

ALTER TABLE
    historicoCompras
ADD
    CONSTRAINT artProv FOREIGN KEY (id_proveedor) references proveedor(id);

ALTER TABLE
    historicoCompras
ADD
    CONSTRAINT artId FOREIGN KEY (id_articulo) references articulos(id);

ALTER TABLE
    articulos
ADD
    CONSTRAINT artSeccion FOREIGN KEY (id_seccion) REFERENCES seccion (id);

ALTER TABLE
    articulos
ADD
    CONSTRAINT fabricante_id FOREIGN KEY (id_fabricante) REFERENCES fabricante (id);

ALTER TABLE
    articulos
ADD
    CONSTRAINT artFamilia FOREIGN KEY (id_familia) REFERENCES seccion (id);

ALTER TABLE
    articuloUbicacion
ADD
    CONSTRAINT ubiSeccion FOREIGN KEY (ubicacion) REFERENCES seccion (codigo);

ALTER TABLE
    articuloUbicacion
ADD
    CONSTRAINT ubiArticulo FOREIGN KEY (id_articulo) REFERENCES articulos (id);

ALTER TABLE
    familia
ADD
    CONSTRAINT familiaSeccion FOREIGN KEY (id_seccion) REFERENCES seccion (id);
ALTER TABLE
    grupo
ADD
    CONSTRAINT grupoFamilia FOREIGN KEY (id_familia) REFERENCES familia (id);

ALTER TABLE
    proveedor
ADD
    CONSTRAINT refFabricante FOREIGN KEY (id_fabricante) REFERENCES fabricante (id);

/*
 drop TABLE articulos;
 drop TABLE articuloubicacion; 
 drop TABLE fabricante; 
 drop TABLE familia; 
 drop TABLE historicocompras; 
 drop TABLE proveedor;
 DROP TABLE seccion;
 DROP TABLE articulofoto;
 DROP TABLE articuloproveedor;*/
/*consultas*/
SELECT
    MAX(codigo)
FROM
    articulos
WHERE
    id_seccion = 1
    AND id_familia = 1
LIMIT
    1;