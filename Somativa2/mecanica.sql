-- Criar banco de dados
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

-- Primeiro criar as ordens de serviço
INSERT INTO ordens_servico (id_veiculo, data_abertura, status, observacoes) VALUES 
(1, '2024-01-15', 'Concluída', 'Troca de óleo realizada'),
(2, '2024-01-16', 'Em Andamento', 'Revisão de freios');

-- Depois inserir os relacionamentos
INSERT INTO os_servicos (id_os, id_servico) VALUES 
(1, 1),
(2, 3);

INSERT INTO os_mecanicos (id_os, id_mecanico) VALUES 
(1, 1),
(2, 2);

-- FINALMENTE inserir as peças (agora as OS existem)
INSERT INTO os_pecas (id_os, id_peca, quantidade) VALUES 
(1, 1, 1),
(1, 2, 4);
