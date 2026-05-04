<?php

require_once __DIR__ . '/../models/Treino.php';
require_once __DIR__ . '/../models/Exercicio.php';

class TreinoController {

    private Treino $treino;
    private Exercicio $exercicio;

    public function __construct(private PDO $pdo) {
        $this->treino    = new Treino($pdo);
        $this->exercicio = new Exercicio($pdo);
    }

    public function index(): void {
        $treinos = $this->treino->getAll();
        $this->json($treinos);
    }

    public function show(int $id): void {
        $treino = $this->treino->getById($id);

        if (!$treino) {
            $this->json(['erro' => 'Treino não encontrado.'], 404);
            return;
        }

        $exercicios = $this->exercicio->getByTreino($id);

        $this->json([
            'treino'     => $treino,
            'exercicios' => $exercicios,
        ]);
    }

    public function store(): void {
        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'nome'           => 'required|max:100',
            'grupo_muscular' => 'required|max:50',
        ]);

        if (!empty($erros)) {
            $this->json(['erros' => $erros], 422);
            return;
        }

        $id = $this->treino->create($dados);
        $this->json(['mensagem' => 'Treino criado com sucesso.', 'id' => $id], 201);
    }

    public function update(int $id): void {
        $treino = $this->treino->getById($id);

        if (!$treino) {
            $this->json(['erro' => 'Treino não encontrado.'], 404);
            return;
        }

        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'nome'           => 'required|max:100',
            'grupo_muscular' => 'required|max:50',
        ]);

        if (!empty($erros)) {
            $this->json(['erros' => $erros], 422);
            return;
        }

        $this->treino->update($id, $dados);
        $this->json(['mensagem' => 'Treino atualizado com sucesso.']);
    }

    public function destroy(int $id): void {
        $treino = $this->treino->getById($id);

        if (!$treino) {
            $this->json(['erro' => 'Treino não encontrado.'], 404);
            return;
        }

        $this->treino->delete($id);
        $this->json(['mensagem' => 'Treino excluído com sucesso.']);
    }

    private function getBody(): array {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    private function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function validar(array $dados, array $regras): array {
        $erros = [];

        foreach ($regras as $campo => $regra) {
            $valor = trim($dados[$campo] ?? '');
            $partes = explode('|', $regra);

            foreach ($partes as $parte) {
                if ($parte === 'required' && empty($valor)) {
                    $erros[$campo] = "O campo {$campo} é obrigatório.";
                    break;
                }

                if (str_starts_with($parte, 'max:')) {
                    $max = (int) explode(':', $parte)[1];
                    if (strlen($valor) > $max) {
                        $erros[$campo] = "O campo {$campo} deve ter no máximo {$max} caracteres.";
                        break;
                    }
                }
            }
        }

        return $erros;
    }
}