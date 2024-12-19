<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: index.php'); // Redireciona para a página de login
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include '../ConexaoBanco/conexao.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = strtoupper($_POST['nome_completo']); // Converte o nome para maiúsculas
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $observacoes = $_POST['observacoes'];

    // Validações básicas
    if (strlen($cpf) != 11) {
        $erro = 'CPF inválido. Certifique-se de que contém 11 dígitos.';
    } elseif (strlen($observacoes) > 50) {
        $erro = 'Observações não podem exceder 50 caracteres.';
    } else {
        try {
            // Prepara a query para inserir os dados no banco de dados
            $query = "INSERT INTO usuarios (nome_completo, cpf, data_nascimento, observacoes) 
                      VALUES (:nome_completo, :cpf, :data_nascimento, :observacoes)";
            
            $stmt = $conexao->prepare($query);
            
            // Vincula os valores aos parâmetros
            $stmt->bindParam(':nome_completo', $nome_completo);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':data_nascimento', $data_nascimento);
            $stmt->bindParam(':observacoes', $observacoes);
            
            // Executa a query
            $stmt->execute();
            
            // Exibe mensagem de sucesso com JavaScript
            echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        } catch (PDOException $e) {
            // Em caso de erro, exibe mensagem de erro
            $erro = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastramento de Usuário</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background-color: #f8f9fa;
    padding-top: 2rem;
  }

  .cadastro-container {
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

  .error {
    color: #dc3545;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 0.75rem;
    border-radius: 4px;
    margin-bottom: 1rem;
  }

  .developer-credit {
    text-align: center;
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 2rem;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 100px;
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="cadastro-container">
      <h2 class="form-title text-center">Cadastramento de Usuário</h2>
      <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="nome_completo" class="form-label">Nome Completo:</label>
          <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
          <div class="invalid-feedback">
            Por favor, insira o nome completo.
          </div>
        </div>

        <div class="mb-3">
          <label for="cpf" class="form-label">CPF:</label>
          <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" required>
          <div class="invalid-feedback">
            Por favor, insira um CPF válido com 11 dígitos.
          </div>
        </div>

        <div class="mb-3">
          <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
          <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
          <div class="invalid-feedback">
            Por favor, selecione a data de nascimento.
          </div>
        </div>

        <div class="mb-4">
          <label for="observacoes" class="form-label">Observações (máx. 50 caracteres):</label>
          <textarea class="form-control" id="observacoes" name="observacoes" maxlength="50"></textarea>
          <div class="invalid-feedback">
            As observações não podem exceder 50 caracteres.
          </div>
        </div>

        <?php if (isset($erro)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $erro; ?>
        </div>
        <?php endif; ?>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
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

  // Formatação do CPF
  document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '')
    e.target.value = value
  })
  </script>
</body>

</html>