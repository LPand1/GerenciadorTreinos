# GymCRUD — Gerenciador de Treinos

Sistema CRUD de gerenciamento de treinos de academia.  
Stack: **PHP | MySQL | HTML | CSS | JavaScript Vanilla**  
Arquitetura: **MVC**

---

## Estrutura de Arquivos

```
gym_crud/
├── index.php                  ← Front Controller (ponto de entrada)
├── banco.sql                  ← Script para criar o banco e tabela
├── config/
│   └── database.php           ← Configuração da conexão MySQL
├── models/
│   └── Treino.php             ← Model: operações no banco (CRUD)
├── controllers/
│   └── TreinoController.php   ← Controller: lógica e validação server-side
├── views/
│   ├── layout/
│   │   ├── header.php         ← Cabeçalho HTML (reutilizável)
│   │   └── footer.php         ← Rodapé HTML (reutilizável)
│   ├── index.php              ← View: listagem de treinos
│   ├── form.php               ← View: formulário criar/editar
│   └── historico.php          ← View: histórico com estatísticas
└── public/
    ├── css/style.css          ← Estilos
    └── js/app.js              ← Validação JS e modal de confirmação
```

---

## Como Rodar

### 1. Banco de Dados
Abra o **phpMyAdmin** ou o MySQL e execute:
```sql
-- Importar o arquivo banco.sql
```
Ou copie e cole o conteúdo de `banco.sql` no terminal MySQL.

### 2. Configurar Conexão
Edite `config/database.php` com suas credenciais:
```php
define('DB_USER', 'root');   // seu usuário MySQL
define('DB_PASS', '');       // sua senha MySQL
```

### 3. Servidor Local
Coloque a pasta `gym_crud/` dentro de:
- **XAMPP**: `htdocs/gym_crud/`
- **WAMP**: `www/gym_crud/`

Acesse: `http://localhost/gym_crud/`

---

## Funcionalidades

| Operação | Rota                          |
|----------|-------------------------------|
| Listar   | `index.php`                   |
| Criar    | `index.php?acao=criar`        |
| Salvar   | `index.php?acao=salvar` (POST)|
| Editar   | `index.php?acao=editar&id=N`  |
| Atualizar| `index.php?acao=atualizar&id=N` (POST)|
| Excluir  | `index.php?acao=excluir&id=N` |
| Histórico| `index.php?acao=historico`    |

---

## Validações

**Client-side (JavaScript):**
- Nome obrigatório e máximo 100 caracteres
- Grupo muscular obrigatório
- Duração obrigatória e positiva
- Data obrigatória

**Server-side (PHP):**
- Mesmas validações replicadas no controller
- Sanitização com `real_escape_string` e cast para `int`
- Proteção contra XSS com `htmlspecialchars` nas views
