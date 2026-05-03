<?php

class Treino implements JsonSerializable {
    private $id;
    private $nome;
    private $grupo_muscular;
    private $descricao;
    private $criado_em;

    public function __construct(private PDO $pdo) {}

    public function getId() { return $this->id; }
    public function setNome($n) { $this->nome = $n; }
    public function getNome() { return $this->nome; }
    public function setGrupo_muscular($gm) { $this->grupo_muscular = $gm; }
    public function getGrupo_muscular() { return $this->grupo_muscular; }
    public function setDescricao($d) { $this->descricao = $d; }
    public function getDescricao() { return $this->descricao; }
    public function setCriado_em($ce) { $this->criado_em = $ce; }
    public function getCriado_em() { return $this->criado_em; }

    public function jsonSerialize(): array {
        return [
            'id'             => $this->id,
            'nome'           => $this->nome,
            'grupo_muscular' => $this->grupo_muscular,
            'descricao'      => $this->descricao,
            'criado_em'      => $this->criado_em,
        ];
    }

    private function hydrate(array $row): self {
        $obj = new self($this->pdo);
        $obj->id             = $row['id'];
        $obj->nome           = $row['nome'];
        $obj->grupo_muscular = $row['grupo_muscular'];
        $obj->descricao      = $row['descricao'];
        $obj->criado_em      = $row['criado_em'];
        return $obj;
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM treinos ORDER BY criado_em DESC");
        $stmt->execute();
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function getById(int $id): ?self {
        $stmt = $this->pdo->prepare("SELECT * FROM treinos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function create(array $dados): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO treinos (nome, grupo_muscular, descricao)
            VALUES (:nome, :grupo_muscular, :descricao)
        ");
        $stmt->execute([
            ':nome'           => $dados['nome'],
            ':grupo_muscular' => $dados['grupo_muscular'],
            ':descricao'      => $dados['descricao'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $dados): bool {
        $stmt = $this->pdo->prepare("
            UPDATE treinos
            SET nome = :nome, grupo_muscular = :grupo_muscular, descricao = :descricao
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nome'           => $dados['nome'],
            ':grupo_muscular' => $dados['grupo_muscular'],
            ':descricao'      => $dados['descricao'] ?? null,
            ':id'             => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM treinos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}