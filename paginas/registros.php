<?php
session_start(); // Inicia a sessão
include '../ConexaoBanco/conexao.php'; // Inclua a conexão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../index.php'); // Redireciona para a página de login
    exit();
}

// Inicializa variáveis
$usuario = null;
$cpf = null;
$motivo = null;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $motivo = $_POST['motivo'];

    try {
        // Verifica se o CPF existe na tabela usuarios
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
        $stmt->execute(['cpf' => $cpf]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Se o CPF existe, insira na tabela de registros
            $stmt = $conexao->prepare("INSERT INTO registros (cpf, nome_completo, data_nascimento, observacoes, motivo) VALUES (:cpf, :nome_completo, :data_nascimento, :observacoes, :motivo)");
            $stmt->execute([
                'cpf' => $usuario['cpf'],
                'nome_completo' => strtoupper($usuario['nome_completo']),
                'data_nascimento' => $usuario['data_nascimento'],
                'observacoes' => $usuario['observacoes'],
                'motivo' => $motivo
            ]);
            echo "<script>alert('Pessoa registrada com sucesso');</script>";
        } else {
            echo "<script>alert('Pessoa não cadastrada');</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acesso de Usuários</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background-color: #f8f9fa;
    padding-top: 2rem;
  }

  .registro-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 2rem;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }

  .form-title {
    color: #004080;
    margin-bottom: 2rem;
    font-weight: 600;
  }

  .form-control {
    border: 1px solid #ced4da;
    padding: 0.75rem;
    margin-bottom: 1rem;
  }

  .form-control:focus {
    border-color: #004080;
    box-shadow: 0 0 0 0.2rem rgba(0, 64, 128, 0.25);
  }

  .form-label {
    font-weight: 500;
    color: #495057;
  }

  .btn-primary {
    background-color: #004080;
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #003366;
    transform: translateY(-1px);
  }

  .btn-secondary {
    background-color: #6c757d;
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
  }

  .btn-secondary:hover {
    background-color: #5a6268;
  }

  .developer-credit {
    text-align: center;
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 2rem;
  }

  select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="registro-container">
      <h2 class="form-title text-center">Registro de Usuário</h2>
      <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="cpf" class="form-label">Digite seu CPF:</label>
          <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" required>
        </div>

        <div class="mb-4">
          <label for="motivo" class="form-label">Selecione o Motivo:</label>
          <select class="form-control" id="motivo" name="motivo" required>
            <option value="">Selecione um motivo</option>
            <option value="saude">Saúde</option>
            <option value="veteranos">Veteranos</option>
            <option value="reuniao">Reunião</option>
            <option value="acompanhar">Acompanhar</option>
            <option value="empresa">Empresa</option>
            <option value="fisioterapia">Fisioterapia</option>
            <option value="outros">Outros</option>
          </select>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Registrar</button>
          <button type="button" onclick="window.location.href='op.php'" class="btn btn-secondary">Voltar</button>
        </div>
      </form>
      <p class="developer-credit">Desenvolvido por MN-RC DIAS 24.0729.23</p>
    </div>
  </div>

  <!-- Bootstrap Bundle com Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para validação do formulário -->
  <script>
  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
  </script>
</body>

</html>