<?php

require_once __DIR__ . '/../models/Sessao.php';
require_once __DIR__ . '/../models/Treino.php';

class SessaoController {

    private Sessao $sessao;
    private Treino $treino;

    public function __construct(private PDO $pdo) {
        $this->sessao = new Sessao($pdo);
        $this->treino = new Treino($pdo);
    }

    public function index(): void {
        $sessoes = $this->sessao->getAll();
        $this->json($sessoes);
    }

    public function show(int $id): void {
        $sessao = $this->sessao->getById($id);

        if (!$sessao) {
            $this->json(['erro' => 'Sessão não encontrada.'], 404);
            return;
        }

        $this->json($sessao);
    }

    public function store(): void {
        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'treino_id' => 'required',
            'data'      => 'required',
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

        if (!$this->validarData($dados['data'])) {
            $this->json(['erros' => ['data' => 'Data inválida. Use o formato AAAA-MM-DD.']], 422);
            return;
        }

        $id = $this->sessao->create($dados);
        $this->json(['mensagem' => 'Sessão registrada com sucesso.', 'id' => $id], 201);
    }

    public function update(int $id): void {
        $sessao = $this->sessao->getById($id);

        if (!$sessao) {
            $this->json(['erro' => 'Sessão não encontrada.'], 404);
            return;
        }

        $dados = $this->getBody();

        $erros = $this->validar($dados, [
            'data' => 'required',
        ]);

        if (!empty($erros)) {
            $this->json(['erros' => $erros], 422);
            return;
        }

        if (!$this->validarData($dados['data'])) {
            $this->json(['erros' => ['data' => 'Data inválida. Use o formato AAAA-MM-DD.']], 422);
            return;
        }

        $this->sessao->update($id, $dados);
        $this->json(['mensagem' => 'Sessão atualizada com sucesso.']);
    }

    public function destroy(int $id): void {
        $sessao = $this->sessao->getById($id);

        if (!$sessao) {
            $this->json(['erro' => 'Sessão não encontrada.'], 404);
            return;
        }

        $this->sessao->delete($id);
        $this->json(['mensagem' => 'Sessão excluída com sucesso.']);
    }

    private function validarData(string $data): bool {
        $d = DateTime::createFromFormat('Y-m-d', $data);
        return $d && $d->format('Y-m-d') === $data;
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