<?php
// models/Treino.php

require_once __DIR__ . '/../config/database.php';

class Treino {

    private $conn;

    public function __construct() {
        $this->conn = conectar();
    }

    // READ - listar todos
    public function listar() {
        $sql = "SELECT * FROM treinos ORDER BY data_treino DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // READ - buscar por ID
    public function buscarPorId($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM treinos WHERE id = $id LIMIT 1";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // CREATE - inserir
    public function inserir($dados) {
        $nome        = $this->conn->real_escape_string($dados['nome']);
        $descricao   = $this->conn->real_escape_string($dados['descricao']);
        $grupo       = $this->conn->real_escape_string($dados['grupo']);
        $duracao     = (int) $dados['duracao'];
        $data_treino = $this->conn->real_escape_string($dados['data_treino']);

        $sql = "INSERT INTO treinos (nome, descricao, grupo, duracao, data_treino)
                VALUES ('$nome', '$descricao', '$grupo', $duracao, '$data_treino')";

        return $this->conn->query($sql);
    }

    // UPDATE - atualizar
    public function atualizar($id, $dados) {
        $id          = (int) $id;
        $nome        = $this->conn->real_escape_string($dados['nome']);
        $descricao   = $this->conn->real_escape_string($dados['descricao']);
        $grupo       = $this->conn->real_escape_string($dados['grupo']);
        $duracao     = (int) $dados['duracao'];
        $data_treino = $this->conn->real_escape_string($dados['data_treino']);

        $sql = "UPDATE treinos SET
                    nome        = '$nome',
                    descricao   = '$descricao',
                    grupo       = '$grupo',
                    duracao     = $duracao,
                    data_treino = '$data_treino'
                WHERE id = $id";

        return $this->conn->query($sql);
    }

    // DELETE - excluir
    public function excluir($id) {
        $id = (int) $id;
        $sql = "DELETE FROM treinos WHERE id = $id";
        return $this->conn->query($sql);
    }
}
