// public/js/app.js

// ===== VALIDAÇÃO DO FORMULÁRIO (client-side) =====
const form = document.getElementById('formTreino');

if (form) {
    form.addEventListener('submit', function (e) {
        let valido = true;

        // Limpar erros anteriores
        document.querySelectorAll('.invalido').forEach(el => el.classList.remove('invalido'));
        document.querySelectorAll('.erro-campo').forEach(el => el.style.display = 'none');

        const nome = document.getElementById('nome');
        const grupo = document.getElementById('grupo');
        const duracao = document.getElementById('duracao');
        const data = document.getElementById('data_treino');

        if (!nome.value.trim()) {
            mostrarErro(nome, 'Nome do treino é obrigatório.');
            valido = false;
        } else if (nome.value.trim().length > 100) {
            mostrarErro(nome, 'Máximo 100 caracteres.');
            valido = false;
        }

        if (!grupo.value) {
            mostrarErro(grupo, 'Selecione um grupo muscular.');
            valido = false;
        }

        if (!duracao.value || parseInt(duracao.value) <= 0) {
            mostrarErro(duracao, 'Informe a duração em minutos (número positivo).');
            valido = false;
        }

        if (!data.value) {
            mostrarErro(data, 'Data do treino é obrigatória.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
}

function mostrarErro(campo, mensagem) {
    campo.classList.add('invalido');
    const span = campo.parentElement.querySelector('.erro-campo');
    if (span) {
        span.textContent = mensagem;
        span.style.display = 'block';
    }
}

// ===== MODAL DE CONFIRMAÇÃO DE EXCLUSÃO =====
const overlay = document.getElementById('overlay');
let urlExcluir = '';

function confirmarExclusao(id, nome) {
    urlExcluir = 'index.php?acao=excluir&id=' + id;
    document.getElementById('nomeModal').textContent = nome;
    overlay.classList.add('aberto');
}

function fecharModal() {
    overlay.classList.remove('aberto');
}

function executarExclusao() {
    window.location.href = urlExcluir;
}

// Fechar modal clicando fora
if (overlay) {
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) fecharModal();
    });
}

// ===== FECHAR ALERTAS =====
document.querySelectorAll('.alerta').forEach(function (el) {
    setTimeout(function () {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 3500);
});
