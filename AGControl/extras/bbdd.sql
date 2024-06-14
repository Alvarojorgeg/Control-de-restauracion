drop database proyecto;
create database proyecto;
use proyecto;

create table cliente (
    cod_mesareservada int(11) not null,
    cod_cliente char(9) not null,
    primary key (cod_mesareservada, cod_cliente)
);

create table comida (
    cod_comida int(11) not null auto_increment,
    nombre varchar(50) not null,
    categoria varchar(50) not null,
    ingredientes varchar(100) not null,
    precio decimal(10,2) not null,
    imagen varchar(50) not null,
    primary key (cod_comida)
);

create table configuracion_mesas (
    id int(11) not null auto_increment,
    num_mesas int(11) default null,
    config_mesas json default null,
    fecha_creacion timestamp default current_timestamp on update current_timestamp,
    primary key (id)
);

create table dia_libre (
    dni varchar(20) not null,
    fecha date not null,
    concedido_sino char(2) not null,
    primary key (dni, fecha)
);

create table empleados (
    dni varchar(20) not null,
    nombre varchar(100) not null,
    apellidos varchar(100) not null,
    pin char(6) not null,
    fechanac date not null,
    direccion varchar(255) not null,
    correo varchar(100) not null,
    telefono varchar(20) not null,
    horario varchar(20) not null,
    nacionalidad varchar(100) not null,
    inicio_contrato date not null,
    categoria varchar(50) not null,
    trabajando char(2) not null,
    nomina varchar(50) not null,
    primary key (dni)
);

create table factura (
    codigo_ticket varchar(50) not null,
    fecha timestamp default current_timestamp on update current_timestamp,
    total decimal(10,2) not null,
    numero_mesa int(11) not null,
    primary key (codigo_ticket)
);

create table mesareservada (
    cod_mesareservada int(11) not null auto_increment,
    cod_cliente char(9) default null,
    nombre varchar(20) not null,
    apellidos varchar(50) not null,
    personas int(11) default null,
    intolerancias varchar(50) default null,
    fecha date default null,
    hora varchar(5) default null,
    primary key (cod_mesareservada)
);

create table pedido (
    id int(11) not null auto_increment,
    numero_mesa int(11) default null,
    comida_id int(11) default null,
    estado varchar(50) default null,
    cantidad int(11) not null default 1,
    primary key (id),
    foreign key (comida_id) references comida(cod_comida)
);
