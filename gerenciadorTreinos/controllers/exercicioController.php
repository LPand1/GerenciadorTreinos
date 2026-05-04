<?php

require_once __DIR__ . '/../models/Exercicio.php';
require_once __DIR__ . '/../models/Treino.php';

class ExercicioController {

    private Exercicio $exercicio;
    private Treino $treino;

    public function __construct(private PDO $pdo) {
        $this->exercicio = new Exercicio($pdo);
        $this->treino    = new Treino($pdo);
    }

    public function store(): void {
        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'treino_id'  => 'required',
            'nome'       => 'required|max:100',
            'series'     => 'required',
            'repeticoes' => 'required|max:20',
        ]);

        if (!empty($erros)) {
            $this->json(['erros' => $erros], 422);
            return;
        }

        $treino = $this->treino->getById((int) $dados['treino_id']);

        if (!$treino) {
            $this->json(['erro' => 'Treino não encontrado.'], 404);
            return;
        }

        $id = $this->exercicio->create($dados);
        $this->json(['mensagem' => 'Exercício criado com sucesso.', 'id' => $id], 201);
    }

    public function update(int $id): void {
        $exercicio = $this->exercicio->getById($id);

        if (!$exercicio) {
            $this->json(['erro' => 'Exercício não encontrado.'], 404);
            return;
        }

        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'nome'       => 'required|max:100',
            'series'     => 'required',
            'repeticoes' => 'required|max:20',
        ]);

        if (!empty($erros)) {
            $this->json(['erros' => $erros], 422);
            return;
        }

        $this->exercicio->update($id, $dados);
        $this->json(['mensagem' => 'Exercício atualizado com sucesso.']);
    }

    public function destroy(int $id): void {
        $exercicio = $this->exercicio->getById($id);

        if (!$exercicio) {
            $this->json(['erro' => 'Exercício não encontrado.'], 404);
            return;
        }

        $this->exercicio->delete($id);
        $this->json(['mensagem' => 'Exercício excluído com sucesso.']);
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
            $valor = trim((string) ($dados[$campo] ?? ''));
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