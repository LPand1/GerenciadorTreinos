CREATE DATABASE IF NOT EXISTS gerenciadorTreinos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gerenciadorTreinos;

CREATE TABLE IF NOT EXISTS treinos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    grupo_muscular VARCHAR(50) NOT NULL,
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS exercicios (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    treino_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    series INT NOT NULL,
    repeticoes VARCHAR(20) NOT NULL,
    carga_kg DECIMAL(5,2),
    FOREIGN KEY (treino_id) REFERENCES treinos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessoes (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    treino_id INT NOT NULL,
    data DATE NOT NULL,
    duracao_min INT,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (treino_id) REFERENCES treinos(id) ON DELETE CASCADE
);