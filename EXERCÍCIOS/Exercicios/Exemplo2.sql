create database solar;

use solar;

create table if not exists clientes (
id_cliente int auto_increment not null,
nome_cliente varchar(100),
CPF_cliente varchar(14) not null,
enredeco_cliente varchar(100),
celular_cliente varchar(19),
primary key(id_cliente)
);

create table if not exists produtos (
id_produtos int auto_increment not null,
nome_produto varchar(100),
descricao varchar(255),
valor decimal(5,2) not null,
quantidade int not null,
primary key(id_produtos)
);

create table if not exists fornecedor (
id_fornecedor int auto_increment not null,
nome_fornecedor varchar(100),
celular_fornecedor varchar(19),
enreco_fornecedor varchar(100),
cidade varchar(40),
estado char(2),
cnpj varchar(19) not null,
primary key(id_fornecedor)
);

create table venda (
id_venda int primary key auto_increment not null,
id_produto int not null,
id_fornecedor int not null,
foreign key (id_produto) references produtos (id_produtos),
foreign key (id_fornecedor) references fornecedor (id_fornecedor)
);

create table comprar (
id_comprar int primary key auto_increment not null,
id_produto int not null,
id_cliente int not null,
foreign key (id_produto) references produtos (id_produtos),
foreign key (id_cliente) references fornecedor (id_cliente)
);

create table departamento (
id_departamento int auto_increment primary key not null,
nome_departamento varchar(20) not null,
responsavel varchar(100) not null,
setor varchar(50) not null
);


create table if not exists funcionarios (
id_funcionarios int auto_increment primary key not null,
nome_funcionario varchar(100) not null,
data_nascimento_funcionario date not null,
cpf_funcionario varchar(14) not null,
enderenco varchar(100) not null,
salario decimal(7,2) not null,
data_admissao datetime not null,
id_departamento int not null,
foreign key (id_departamento) references departamento (id_departamento)
);

select * from funcionarios;

alter table funcionarios
add sexo char(1) not null;

alter table funcionarios
drop column sexo;

alter table funcionarios
rename to empregado;

alter table empregado
change cpf_funcionario cic_funcionario varchar(18);

alter table empregado
modify column nome_funcionario varchar(200);

alter table fornecedor
modify column estado char (2) default 'MG';

alter table empregado
add primary key (cpf_funcionario);

alter table empregado modify cpf_funcionario int not null;
alter table empregado drop primary key;

alter table empregado
add primary key (cod_funcionario,cpf_funcionario);

create table tipo_produtos (
cod_tipo_produto int auto_increment primary key not null,
tipo_produto varchar(255) not null,
index (cod_tipo_produto)
);






