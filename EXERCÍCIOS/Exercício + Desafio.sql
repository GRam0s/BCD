CREATE DATABASE remoterc;

USE remoterc;

CREATE TABLE produtos (
    CProd INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Descricao VARCHAR(200),
    Peso INT NOT NULL,
    ValorUnit DECIMAL(10,2) NOT NULL	
);

INSERT INTO produtos (Descricao, Peso, ValorUnit)
VALUES ("Teclado", 1, 35.00);

ALTER TABLE produtos MODIFY Peso DECIMAL(5,2) NOT NULL;

INSERT INTO produtos (Descricao, Peso, ValorUnit) 
VALUES ("Mouse", 0.5, 25.00);

INSERT INTO produtos (Descricao, Peso, ValorUnit) 
VALUES ("HD", 2, 350.00); 	

CREATE TABLE VENDEDOR (
    CVend INT PRIMARY KEY,
    Nome VARCHAR(100),
    Salario DECIMAL(10,2),
    FSalario INT
);

INSERT INTO VENDEDOR (CVend, Nome, Salario, FSalario) VALUES
(1, 'Ronaldo', 3512.00, 1),
(2, 'Robertson', 3225.00, 2),
(3, 'Clodoaldo', 4350.00, 3);

CREATE TABLE CLIENTE (
    CCli INT PRIMARY KEY,
    Nome VARCHAR(100),
    Endereco VARCHAR(200),
    Cidade VARCHAR(100),
    UF CHAR(2)
);

INSERT INTO CLIENTE (CCli, Nome, Endereco, Cidade, UF) VALUES
(11, 'Bruno', 'Rua 1 456', 'Rio Claro', 'SP'),
(12, 'Cláudio', 'Rua Quadrada 234', 'Campinas', 'SP'),
(13, 'Cremilda', 'Rua das Flores 666', 'São Paulo', 'SP');

SELECT * FROM produtos;

SELECT * FROM vendedor;

SELECT * FROM cliente;

UPDATE VENDEDOR
SET Salario = 3150.00
WHERE CVend = 1;

UPDATE VENDEDOR
SET Salario = Salario * 1.10
WHERE CVend = 2;

UPDATE VENDEDOR
SET Salario = 3500.00
WHERE CVend = 3;

SELECT * FROM vendedor;