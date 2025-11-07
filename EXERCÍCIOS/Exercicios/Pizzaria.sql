create database pizzaria;

use pizzaria;

create table pizza (
id_aluno int primary key,
ingredientes char (100),
nome_pizza char (30),
preco int
);

create table cliente (
id_cliente int primary key,
usuario_cliente char (20),
senha_cliente char (15),
celular_cliente char (11)
);

create table administrador (
id_admin int primary key,
usuario_admin char (15),
senha_admin char (15),
email_admin char (30)
);
