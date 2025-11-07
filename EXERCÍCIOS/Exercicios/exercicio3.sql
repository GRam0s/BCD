USE senai_banco;

ALTER TABLE Projeto DROP FOREIGN KEY projeto_ibfk_1;
ALTER TABLE Projeto DROP COLUMN Icod;

CREATE TABLE Cidade (
  Ccod INT PRIMARY KEY AUTO_INCREMENT,
  Cnome VARCHAR(100) NOT NULL,
  uf CHAR(2) NOT NULL
);

ALTER TABLE Fornecedor 
  ADD Fone VARCHAR(20),
  ADD Ccod INT,
  ADD CONSTRAINT fk_fornecedor_cidade FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod);

ALTER TABLE Peca DROP COLUMN Cidade;
ALTER TABLE Peca 
  ADD Ccod INT,
  ADD CONSTRAINT fk_peca_cidade FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod);

ALTER TABLE Projeto DROP COLUMN Cidade;
ALTER TABLE Projeto 
  ADD Ccod INT,
  ADD CONSTRAINT fk_projeto_cidade FOREIGN KEY (Ccod) REFERENCES Cidade(Ccod);

DROP TABLE Instituicao;
