create database senai_banco;

USE senai_banco;

CREATE INDEX idx_fornecedor_nome ON Fornecedor(Fnome);
CREATE INDEX idx_peca_nome ON Peca(Pnome);
CREATE INDEX idx_projeto_nome ON Projeto(PRnome);

CREATE INDEX idx_fornecimento_fcod ON Fornecimento(Fcod);
CREATE INDEX idx_fornecimento_pcod ON Fornecimento(Pcod);
CREATE INDEX idx_fornecimento_prcod ON Fornecimento(PRcod);