<?php

class Exercicio implements JsonSerializable {
    private $id;
    private $treino_id;
    private $nome;
    private $series;
    private $repeticoes;
    private $carga_kg;

    public function __construct(private PDO $pdo) {}

    public function getId() { return $this->id; }
    public function getTreino_id() { return $this->treino_id; }
    public function setNome($n) { $this->nome = $n; }
    public function getNome() { return $this->nome; }
    public function setSeries($s) { $this->series = $s; }
    public function getSeries() { return $this->series; }
    public function setRepeticoes($r) { $this->repeticoes = $r; }
    public function getRepeticoes() { return $this->repeticoes; }
    public function setCarga_kg($c) { $this->carga_kg = $c; }
    public function getCarga_kg() { return $this->carga_kg; }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'treino_id' => $this->treino_id,
            'nome' => $this->nome,
            'series' => $this->series,
            'repeticoes' => $this->repeticoes,
            'carga_kg' => $this->carga_kg,
        ];
    }

    private function hydrate(array $row): self {
        $obj = new self($this->pdo);
        $obj->id = $row['id'];
        $obj->treino_id = $row['treino_id'];
        $obj->nome = $row['nome'];
        $obj->series = $row['series'];
        $obj->repeticoes = $row['repeticoes'];
        $obj->carga_kg = $row['carga_kg'];
        return $obj;
    }

    public function getByTreino(int $treino_id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM exercicios WHERE treino_id = :treino_id");
        $stmt->execute([':treino_id' => $treino_id]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function getById(int $id): ?self {
        $stmt = $this->pdo->prepare("SELECT * FROM exercicios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function create(array $dados): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO exercicios (treino_id, nome, series, repeticoes, carga_kg)
            VALUES (:treino_id, :nome, :series, :repeticoes, :carga_kg)
        ");
        $stmt->execute([
            ':treino_id' => $dados['treino_id'],
            ':nome' => $dados['nome'],
            ':series' => $dados['series'],
            ':repeticoes' => $dados['repeticoes'],
            ':carga_kg' => $dados['carga_kg'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $dados): bool {
        $stmt = $this->pdo->prepare("
            UPDATE exercicios
            SET nome = :nome, series = :series, repeticoes = :repeticoes, carga_kg = :carga_kg
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':series' => $dados['series'],
            ':repeticoes' => $dados['repeticoes'],
            ':carga_kg' => $dados['carga_kg'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM exercicios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}