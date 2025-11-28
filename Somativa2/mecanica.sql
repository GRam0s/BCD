CREATE DATABASE IF NOT EXISTS oficina_mecanica;
USE oficina_mecanica;

-- Clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100)
);

-- Veículos
CREATE TABLE veiculos (
    id_veiculo INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT,
    placa VARCHAR(10) UNIQUE NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
);

-- Mecânicos
CREATE TABLE mecanicos (
    id_mecanico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100)
);

-- Serviços
CREATE TABLE servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    nome_servico VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_mao_obra DECIMAL(10,2) NOT NULL,
    tempo_estimado VARCHAR(50)
);

-- Peças
CREATE TABLE pecas (
    id_peca INT AUTO_INCREMENT PRIMARY KEY,
    nome_peca VARCHAR(100) NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT DEFAULT 0
);

-- Ordens de Serviço
CREATE TABLE ordens_servico (
    id_os INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    data_abertura DATE,
    status VARCHAR(20) DEFAULT 'Aberta',
    observacoes TEXT,
    FOREIGN KEY (id_veiculo) REFERENCES veiculos(id_veiculo) ON DELETE CASCADE
);

-- Mecânicos por OS
CREATE TABLE os_mecanicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_os INT NOT NULL,
    id_mecanico INT NOT NULL,
    FOREIGN KEY (id_os) REFERENCES ordens_servico(id_os) ON DELETE CASCADE,
    FOREIGN KEY (id_mecanico) REFERENCES mecanicos(id_mecanico) ON DELETE CASCADE
);

-- Serviços por OS
CREATE TABLE os_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_os INT NOT NULL,
    id_servico INT NOT NULL,
    observacoes TEXT,
    FOREIGN KEY (id_os) REFERENCES ordens_servico(id_os) ON DELETE CASCADE,
    FOREIGN KEY (id_servico) REFERENCES servicos(id_servico) ON DELETE CASCADE
);

-- Peças por OS
CREATE TABLE os_pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_os INT NOT NULL,
    id_peca INT NOT NULL,
    quantidade INT DEFAULT 1,
    FOREIGN KEY (id_os) REFERENCES ordens_servico(id_os) ON DELETE CASCADE,
    FOREIGN KEY (id_peca) REFERENCES pecas(id_peca) ON DELETE CASCADE
);

-- Inserir dados básicos
INSERT INTO servicos (nome_servico, descricao, preco_mao_obra, tempo_estimado) VALUES 
('Troca de Óleo e Filtro', 'Troca completa de óleo do motor e filtro de óleo', 80.00, '1 hora'),
('Alinhamento e Balanceamento', 'Alinhamento da direção e balanceamento das rodas', 120.00, '1.5 horas'),
('Revisão de Freios', 'Troca de pastilhas, discos e fluido de freio', 200.00, '2 horas');

INSERT INTO mecanicos (nome, especialidade) VALUES 
('João Silva', 'Motor e Transmissão'),
('Maria Santos', 'Suspensão e Freios');

INSERT INTO clientes (nome, telefone, email) VALUES 
('Carlos Eduardo', '(11) 9999-8888', 'carlos@email.com'),
('Mariana Souza', '(11) 7777-6666', 'mariana@email.com');

INSERT INTO veiculos (id_cliente, marca, modelo, ano, placa) VALUES 
(1, 'Volkswagen', 'Gol', 2020, 'ABC1D23'),
(2, 'Fiat', 'Uno', 2018, 'DEF4G56');

INSERT INTO pecas (nome_peca, preco_unitario, quantidade_estoque) VALUES 
('Filtro de Óleo', 25.00, 50),
('Óleo Motor 5W30', 45.00, 100);

INSERT INTO ordens_servico (id_veiculo, data_abertura, status, observacoes) VALUES 
(1, '2024-01-15', 'Concluída', 'Troca de óleo realizada'),
(2, '2024-01-16', 'Em Andamento', 'Revisão de freios');

INSERT INTO os_servicos (id_os, id_servico) VALUES 
(1, 1),
(2, 3);

INSERT INTO os_mecanicos (id_os, id_mecanico) VALUES 
(1, 1),
(2, 2);

INSERT INTO os_pecas (id_os, id_peca, quantidade) VALUES 
(1, 1, 1),
(1, 2, 4);

SELECT * FROM veiculos
WHERE marca = 'Ford';

SELECT DISTINCT c.*
FROM clientes c
JOIN veiculos v ON v.id_cliente = c.id_cliente
JOIN ordens_servico os ON os.id_veiculo = v.id_veiculo
WHERE os.data_abertura >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH);

SELECT * FROM mecanicos
WHERE especialidade = 'Injeção Eletrônica';

SELECT * FROM ordens_servico
WHERE status = 'Aguardando Peça';

SELECT * FROM pecas
WHERE quantidade_estoque < 5;

SELECT v.*
FROM veiculos v
WHERE (
    SELECT COUNT(*) FROM ordens_servico os
    WHERE os.id_veiculo = v.id_veiculo
) > 1;

SELECT os.*
FROM ordens_servico os
JOIN os_mecanicos om ON om.id_os = os.id_os
WHERE om.id_mecanico = 3;

-- (Desafio) -- listar nome e preco_venda de peças cujo preco_custo > R$ 200,00.
SELECT nome_peca, preco_venda
FROM pecas
WHERE preco_custo > 200.00;

-- 2) UPDATE (Atualizações de dados)
ALTER TABLE pecas
    ADD COLUMN preco_custo DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN preco_venda DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN fabricante VARCHAR(100) DEFAULT NULL;

UPDATE pecas
SET preco_venda = preco_venda * 1.05
WHERE fabricante = 'Bosch';

UPDATE ordens_servico
SET status = 'Concluída'
WHERE id_os = 105 AND status = 'Em Execução';

UPDATE ordens_servico
SET data_conclusao = CURDATE()
WHERE status = 'Em Execução'
  AND data_abertura < DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- (Desafio) Dobre a quantidade em estoque da peça com id_peca = 20.
UPDATE pecas
SET quantidade_estoque = quantidade_estoque * 2
WHERE id_peca = 20;

ALTER TABLE mecanicos
    MODIFY COLUMN especialidade VARCHAR(150);

-- (Desafio) Adicionar restrição CHECK na tabela Pecas para garantir preco_venda >= preco_custo.
ALTER TABLE pecas
    ADD CONSTRAINT chk_preco_venda_custo CHECK (preco_venda >= preco_custo);

-- 4) JOIN (Consultas com múltiplas tabelas) 
SELECT os.id_os, c.nome AS cliente, v.placa, os.data_abertura, os.status, os.observacoes
FROM ordens_servico os
LEFT JOIN veiculos v ON v.id_veiculo = os.id_veiculo
LEFT JOIN clientes c ON c.id_cliente = v.id_cliente
ORDER BY os.data_abertura DESC;

SELECT p.id_peca, p.nome_peca, op.quantidade AS quantidade_usada
FROM os_pecas op
JOIN pecas p ON p.id_peca = op.id_peca
WHERE op.id_os = 50;

SELECT m.id_mecanico, m.nome
FROM os_mecanicos om
JOIN mecanicos m ON m.id_mecanico = om.id_mecanico
WHERE om.id_os = 75;

-- (Desafio) Liste todos os veículos (placa e modelo) cadastrados e o nome do seu respectivo proprietário.
SELECT v.placa, v.modelo, c.nome AS proprietario
FROM veiculos v
JOIN clientes c ON c.id_cliente = v.id_cliente
ORDER BY c.nome, v.modelo;

-- 5) INNER JOIN 
SELECT DISTINCT v.placa, v.modelo
FROM veiculos v
INNER JOIN ordens_servico os ON os.id_veiculo = v.id_veiculo
WHERE os.status = 'Em Execução';

SELECT DISTINCT c.nome
FROM clientes c
INNER JOIN veiculos v ON v.id_cliente = c.id_cliente
WHERE v.marca = 'Volkswagen';

SELECT DISTINCT m.nome
FROM mecanicos m
INNER JOIN os_mecanicos om ON om.id_mecanico = m.id_mecanico;

-- (Desafio) Liste apenas os nomes dos serviços que já foram executados (aparecem em os_servicos).
SELECT DISTINCT s.nome_servico
FROM servicos s
INNER JOIN os_servicos os_s ON os_s.id_servico = s.id_servico;
