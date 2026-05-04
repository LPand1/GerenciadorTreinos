<?php

class Sessao implements JsonSerializable {
    private $id;
    private $treino_id;
    private $data;
    private $duracao_min;
    private $observacoes;
    private $criado_em;
    private $treino_nome;

    public function __construct(private PDO $pdo) {}

    public function getId() { return $this->id; }
    public function getTreino_id() { return $this->treino_id; }
    public function setData($d) { $this->data = $d; }
    public function getData() { return $this->data; }
    public function setDuracao_min($dm) { $this->duracao_min = $dm; }
    public function getDuracao_min() { return $this->duracao_min; }
    public function setObservacoes($o) { $this->observacoes = $o; }
    public function getObservacoes() { return $this->observacoes; }
    public function setCriado_em($ce) { $this->criado_em = $ce; }
    public function getCriado_em() { return $this->criado_em; }
    public function getTreino_nome() { return $this->treino_nome; }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'treino_id' => $this->treino_id,
            'treino_nome' => $this->treino_nome,
            'data' => $this->data,
            'duracao_min' => $this->duracao_min,
            'observacoes' => $this->observacoes,
            'criado_em' => $this->criado_em,
        ];
    }

    private function hydrate(array $row): self {
        $obj = new self($this->pdo);
        $obj->id = $row['id'];
        $obj->treino_id = $row['treino_id'];
        $obj->data = $row['data'];
        $obj->duracao_min = $row['duracao_min'];
        $obj->observacoes = $row['observacoes'];
        $obj->criado_em = $row['criado_em'];
        $obj->treino_nome = $row['treino_nome'] ?? null;
        return $obj;
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare("
            SELECT s.*, t.nome AS treino_nome
            FROM sessoes s
            JOIN treinos t ON t.id = s.treino_id
            ORDER BY s.data DESC
        ");
        $stmt->execute();
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function getById(int $id): ?self {
        $stmt = $this->pdo->prepare("
            SELECT s.*, t.nome AS treino_nome
            FROM sessoes s
            JOIN treinos t ON t.id = s.treino_id
            WHERE s.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function getByTreino(int $treino_id): array {
        $stmt = $this->pdo->prepare("
            SELECT s.*, t.nome AS treino_nome
            FROM sessoes s
            JOIN treinos t ON t.id = s.treino_id
            WHERE s.treino_id = :treino_id
            ORDER BY s.data DESC
        ");
        $stmt->execute([':treino_id' => $treino_id]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(array $dados): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO sessoes (treino_id, data, duracao_min, observacoes)
            VALUES (:treino_id, :data, :duracao_min, :observacoes)
        ");
        $stmt->execute([
            ':treino_id' => $dados['treino_id'],
            ':data' => $dados['data'],
            ':duracao_min' => $dados['duracao_min'] ?? null,
            ':observacoes' => $dados['observacoes'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $dados): bool {
        $stmt = $this->pdo->prepare("
            UPDATE sessoes
            SET data = :data, duracao_min = :duracao_min, observacoes = :observacoes
            WHERE id = :id
        ");
        return $stmt->execute([
            ':data' => $dados['data'],
            ':duracao_min' => $dados['duracao_min'] ?? null,
            ':observacoes' => $dados['observacoes'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM sessoes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}