create database loja_limpeza;

use loja_limpeza;

create table produtos (
id_produto int,
imagem_produto blob,
preco_produto decimal,
descricao_produto varchar(255)
);

create table estoque (
id_estoque int,
validade_produtos date,
observacao varchar (150),
local_estoque varchar (15),
quantidade_produtos int
);

create table funcionarios (
id_funcionarios int,
usuario_funcionario varchar (20),
senha_funcionario varchar (20),
cpf_funcionario varchar (14),
salario_funcionario decimal
);

create table cliente (
id_cliente int,
endereco_cliente varchar (100),
usuario_cliente varchar (20),
senha_cliente varchar (20),
cpf_cliente varchar(14)
);

create table pedidos (
id_pedidos int,
quantidade_pedids int,
cliente_pedido varchar(20),
numero_pedido int,
valor_pedido decimal
);

	


