create database loja_equipamentos;

use loja_equipamentos;

create table if not exists equipamento (
id_equipamento int auto_increment primary key,
nome_equipamento varchar(50) not null,
quantidade_equipamento int not null,
descricao varchar(255) not null,
preco_reserva decimal not null
);

create table fornecedor (
id_fornecedor int auto_increment primary key,
nome_fornecedor varchar(100) not null,
cpf_fornecedor varchar(14) not null,
endereco_fornecedor varchar(150) not null,
uf_fornecedor char(2) not null
);

create table if not exists cliente (
id_cliente int auto_increment primary key,
nome_cliente varchar(100) not null,
cpf_cliente varchar(14) not null,
enredeco_cliente varchar(100) not null,
uf_cliente char(2) not null
);

create table possui (
id_possui int primary key auto_increment not null,
id_produto int not null,
id_equipamentos int not null,
foreign key (id_equipamento) references produtos (id_equipamentos),
foreign key (id_fornecedor) references fornecedor (id_fornecedor)
);

create table reserva (
id_reserva int primary key auto_increment not null,
id_equipamento int not null,
id_cliente int not null,
foreign key (id_equipamento) references produtos (id_equipamento),
foreign key (id_produto) references fornecedor (id_produto)
);
