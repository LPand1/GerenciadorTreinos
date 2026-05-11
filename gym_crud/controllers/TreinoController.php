<?php
// controllers/TreinoController.php

require_once __DIR__ . '/../models/Treino.php';

class TreinoController {

    private $model;

    public function __construct() {
        $this->model = new Treino();
    }

    // Página inicial - lista todos os treinos
    public function index() {
        $treinos = $this->model->listar();
        require __DIR__ . '/../views/index.php';
    }

    // Exibe formulário de criação
    public function criar() {
        require __DIR__ . '/../views/form.php';
    }

    // Salva novo treino (POST)
    public function salvar() {
        $erros = $this->validar($_POST);

        if (!empty($erros)) {
            $dados = $_POST;
            require __DIR__ . '/../views/form.php';
            return;
        }

        $this->model->inserir($_POST);
        header('Location: index.php?msg=criado');
        exit;
    }

    // Exibe formulário de edição
    public function editar($id) {
        $treino = $this->model->buscarPorId($id);
        if (!$treino) {
            header('Location: index.php?msg=nao_encontrado');
            exit;
        }
        require __DIR__ . '/../views/form.php';
    }

    // Atualiza treino (POST)
    public function atualizar($id) {
        $erros = $this->validar($_POST);

        if (!empty($erros)) {
            $treino = $_POST;
            $treino['id'] = $id;
            require __DIR__ . '/../views/form.php';
            return;
        }

        $this->model->atualizar($id, $_POST);
        header('Location: index.php?msg=editado');
        exit;
    }

    // Exclui treino
    public function excluir($id) {
        $this->model->excluir($id);
        header('Location: index.php?msg=excluido');
        exit;
    }

    // Visualizar treino individual
    public function ver($id) {
        $treino = $this->model->buscarPorId($id);
        if (!$treino) {
            header('Location: index.php?msg=nao_encontrado');
            exit;
        }
        require __DIR__ . '/../views/detalhe.php';
    }

    // Histórico de treinos
    public function historico() {
        $treinos = $this->model->listar();
        require __DIR__ . '/../views/historico.php';
    }

    // Validação server-side
    private function validar($dados) {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros[] = 'Nome do treino é obrigatório.';
        } elseif (strlen($dados['nome']) > 100) {
            $erros[] = 'Nome deve ter no máximo 100 caracteres.';
        }

        if (empty(trim($dados['grupo']))) {
            $erros[] = 'Grupo muscular é obrigatório.';
        }

        if (empty($dados['duracao']) || (int)$dados['duracao'] <= 0) {
            $erros[] = 'Duração deve ser um número positivo.';
        }

        if (empty($dados['data_treino'])) {
            $erros[] = 'Data do treino é obrigatória.';
        }

        return $erros;
    }
}
