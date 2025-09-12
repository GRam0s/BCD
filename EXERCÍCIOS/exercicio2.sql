-- Criando o banco de dados
CREATE DATABASE empresa;
USE empresa;

-- Tabela Fornecedor
CREATE TABLE Fornecedor (
    Fcodigo INT PRIMARY KEY AUTO_INCREMENT,
    Fnome VARCHAR(100) NOT NULL,
    Status VARCHAR(20) DEFAULT 'Ativo',
    Cidade VARCHAR(100)
);

-- Tabela Peca
CREATE TABLE Peca (
    Pcodigo INT PRIMARY KEY AUTO_INCREMENT,
    Pnome VARCHAR(100) NOT NULL,
    Cor VARCHAR(50) NOT NULL,
    Peso DECIMAL(5,2) NOT NULL,
    Cidade VARCHAR(100) NOT NULL
);

-- Tabela Instituicao
CREATE TABLE Instituicao (
    Icodigo INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(100) NOT NULL
);

-- Tabela Projeto
CREATE TABLE Projeto (
    PRcod INT PRIMARY KEY AUTO_INCREMENT,
    PRnome VARCHAR(100) NOT NULL,
    Cidade VARCHAR(100),
    Icod INT,
    FOREIGN KEY (Icod) REFERENCES Instituicao(Icodigo)
);

-- Tabela Fornecimento (tabela associativa entre Fornecedor, Peca e Projeto)
CREATE TABLE Fornecimento (
    Fcod INT,
    Pcod INT,
    PRcod INT,
    Quantidade INT NOT NULL,
    PRIMARY KEY (Fcod, Pcod, PRcod),
    FOREIGN KEY (Fcod) REFERENCES Fornecedor(Fcodigo),
    FOREIGN KEY (Pcod) REFERENCES Peca(Pcodigo),
    FOREIGN KEY (PRcod) REFERENCES Projeto(PRcod)
);

-- Apagando as tabelas antigas que mudaram
DROP TABLE IF EXISTS Fornecimento;
DROP TABLE IF EXISTS Projeto;
DROP TABLE IF EXISTS Peca;
DROP TABLE IF EXISTS Fornecedor;
DROP TABLE IF EXISTS Instituicao;

-- Criando nova tabela Cidade
CREATE TABLE Cidade (
    Ccod INT PRIMARY KEY AUTO_INCREMENT,
    Cnome VARCHAR(100) NOT NULL,
    UF CHAR(2) NOT NULL
);

-- Criando tabela Fornecedor
CREATE TABLE Fornecedor (
    Fcod INT PRIMARY KEY AUTO_INCREMENT,
    Fnome VARCHAR(100) NOT NULL,
    Status VARCHAR(20) DEFAULT 'Ativo',
    Fone VARCHAR(20),
    Ccod INT,
    FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod)
);

-- Criando tabela Peca
CREATE TABLE Peca (
    Pcod INT PRIMARY KEY AUTO_INCREMENT,
    Pnome VARCHAR(100) NOT NULL,
    Cor VARCHAR(50) NOT NULL,
    Peso DECIMAL(5,2) NOT NULL,
    Ccod INT NOT NULL,
    FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod)
);

-- Criando tabela Projeto
CREATE TABLE Projeto (
    PRcod INT PRIMARY KEY AUTO_INCREMENT,
    PRnome VARCHAR(100) NOT NULL,
    Ccod INT NOT NULL,
    FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod)
);

-- Criando tabela Fornecimento
CREATE TABLE Fornecimento (
    Fcod INT,
    Pcod INT,
    PRcod INT,
    Quantidade INT NOT NULL,
    PRIMARY KEY (Fcod, Pcod, PRcod),
    FOREIGN KEY (Fcod) REFERENCES Fornecedor(Fcod),
    FOREIGN KEY (Pcod) REFERENCES Peca(Pcod),
    FOREIGN KEY (PRcod) REFERENCES Projeto(PRcod)
);
