CREATE DATABASE IF NOT EXISTS gym_crud CHARACTER SET utf8 COLLATE utf8_general_ci;

USE gym_crud;

DROP TABLE IF EXISTS treinos;

CREATE TABLE IF NOT EXISTS treinos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    descricao   TEXT,
    grupo       VARCHAR(50)  NOT NULL,
    duracao     INT          NOT NULL COMMENT 'em minutos',
    data_treino DATE         NOT NULL,
    criado_em   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);
                                          
-- Dados de exemplo
-- INSERT INTO treinos (nome, descricao, grupo, duracao, data_treino) VALUES
-- ('Peito e Tríceps',  'Supino, crucifixo e tríceps corda',  'Peito',  60, CURDATE()),
-- ('Costas e Bíceps',  'Remada, puxada e rosca direta',       'Costas', 55, DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
-- ('Pernas',           'Agachamento, leg press e cadeira',    'Pernas', 70, DATE_SUB(CURDATE(), INTERVAL 2 DAY));
